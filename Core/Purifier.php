<?php
namespace Core;

use Exception;

class Purifier {
    public function __construct ($data) {
        $this->data = $data;
    }

    public function processArr ($arr) {
        $filtered = [];

        foreach ($arr as $data) {
            $name = gettype($data) == 'string' ? $data : $data['name'];
            $obj = gettype($data) == 'string' ? [] : $data;

            $filtered[$name] = $this->process($name, $obj);
        }
    
        return $filtered;
    }

    public function process ($name, $obj = []) {
        try  {
            
            if (!($name ?? false)) throw new Exception ("Model name should not be empty!");
            $this->name = $name;

            if (!empty($obj)) $this->obj = $obj;
            else if (!($this->model [$name] ?? false)) throw new Exception ("No model found of '{$name}' name!");
            else $this->obj = $this->model [$name];
            
            $this->fillObj ();

            $value = $this->data->{$this->name} ?? false;

            // Check Value Exists or not
            if (!$value && ($this->obj['null'] ?? false)) return'';
            else if (!$value && ($this->obj['default'] ?? false)) return $this->obj['default'];
            else if (!$value) throw new Exception ("{$this->obj['name']} is required!");

            
            $this->value = ($this->obj['lwCase'] ?? false) ? strtolower($value) : $value;

            $this->sanitize();

            $this->validate();

            return $this->value;

        } catch (Exception $ex) {
            throw new Exception ($ex->getMessage());
        }
    }

    public function sanitize () {
        switch ($this->obj['type']) {
            case "int":
                $this->value = filter_var($this->value, FILTER_VALIDATE_INT);

                break;
            case "string":
                $this->value = filter_var($this->value, FILTER_SANITIZE_STRING);

                break;
            case "content":
                $this->value = $this->sanitizeString($this->value);

                break;
            case "double":
                $this->value = is_numeric($this->value) ? $this->value : false;

                break;
            case "float":
                $this->value = is_numeric($this->value) ? $this->value : false;

                break;
            case "email":
                $this->value = filter_var($this->value, FILTER_VALIDATE_EMAIL);

                break;
            case "password":
                if (strlen($this->value) < 8 || strlen($this->value) > 12) $this->value = false;
                else $this->value = password_hash($this->value, PASSWORD_DEFAULT);

                break;
            case "time":
                if (!$this->isValidTimeStamp($this->value)) throw new Exception ("Time stamp is not valid!");

                break;
            case "date":
                if (!$this->valDate($this->value)) throw new Exception ("Date format is invalid!");

                break;
            case "url":
                if (!$this->valUrl($this->value)) throw new Exception ("Url is invalid!");

                break;
            default:
                throw new Exception ("Type of '{$this->obj['name']}' is invalid!");
        }

        if (!$this->value) {
            if (isset($this->obj['valMsg'])) throw new Exception ($this->obj['valMsg']);
            else throw new Exception ($this->obj['name']." is invalid!");
        }
    }

    public function validate () {
        try {
            if (isset($this->obj['values'])) {
                if (!in_array($this->value, $this->obj['values'])) {
                    throw new Exception($this->obj['name']." is invalid!");
                }
            }

        } catch (Exception $ex) {
            throw new Exception ($ex->getMessage());
        }
    }

    public function fillObj () {
        if (!isset($this->obj['name'])) $this->obj['name'] = ucfirst($this->name);
    }

    public function isValidTimeStamp($date_time_string) {
        if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/", $date_time_string)) {
            return true;
        }
        return false;
    }

    public function valDate ($d) {
        try {
            $date = DateTime::createFromFormat('Y-m-d', $d);
            if ($date && $date->format('Y-m-d')) return true;
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function valUrl ($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return true;
    }

    public function sanitizeString($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}