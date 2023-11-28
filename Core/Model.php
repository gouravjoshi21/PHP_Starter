<?php
namespace Core;

use \PDO;
use \PDOException;
use \Exception;

class Model {
    public $conn;

    public function __construct ($conn) {
        $this->conn = $conn;
    }
    
    public function query ($query, $params = [], $bind = false) {
        try {
            $arr = $this->fields[$this->field ?? ''] ?? [];

            $this->statement = $this->conn->prepare($query);
            
            $params = empty($params) ? $this->getArrByKeys($arr) : $params;

            if ($bind) {
                foreach ($params as $key => $value) {
                    $type = $this->model[$key]['type'] ?? '';
    
                    if ($type == 'int') {
                        // $value = intval($this->postData[$key], 10);
                        $this->statement->bindParam(":{$key}", $value, PDO::PARAM_INT);
                    } else {
                        // $value = $this->postData[$key];
                        $this->statement->bindValue(":{$key}", $value, PDO::PARAM_STR);
                    }
                }
    
                $this->statement->execute();
            } else {
                $this->statement->execute($params);
            }
    
            return $this;
        } catch (PDOException $e) {
            throw new Exception ($e->getMessage());
        }
    }

    public function create () {
        $this->field = 'create';

        $query = "INSERT INTO {$this->tbName} SET {$this->createSetString()}";
        
        $this->query($query, $this->postData);

        return $this->conn->lastInsertId();
    }

    public function update () {
        $this->field = 'update';

        $query = "UPDATE {$this->tbName} SET {$this->createSetString()} WHERE id = :id";
        
        array_push($this->fields[$this->field], 'id');

        $this->query($query);

        return true;
    }

    public function getAll () {
        $query = "SELECT {$this->theseFields('gets')} FROM {$this->tbName} {$this->orderBy()}";

        return $this->query($query)->statement->fetchAll();
    }

    public function getBy ($field = 'id') {
        $query = "SELECT {$this->theseFields('get')} FROM {$this->tbName}
            WHERE {$this->model[$field]['sql']} = :field
            {$this->orderBy()}";

        return $this->query($query, ['field' => $this->postData[$field]])->statement->fetchAll();
    }

    public function get () {
        return $this->statement->fetchAll();
    }

    public function fetch () {
        return $this->statement->fetch();
    }

    public function theseFields ($field) {
        return implode(', ', $this->fields[$field]);
    }

    public function orderBy ($by = 'id', $in = 'DESC') {
        return "ORDER BY {$by} {$in}";
    }

    public function createSetString () {
        $arr = [];
        
        foreach($this->fields[$this->field] as $val) {
            $value = $this->model[$val]['sql']." = :{$val}";

            array_push($arr, $value);
        }

        return implode(', ', $arr);
    }

    public function getArrByKeys ($keys) {
        $arr = [];

        foreach ($keys as $key) {
            $arr[$key] = $this->postData[$key];
        }

        return $arr;
    }

    public function getCurrUtcTime ($diff = 0) {
        $currTimestamp = time();

        $timestamp = $currTimestamp + $diff;

        $date_format = "Y-m-d H:i:s";

        $global_timestamp = gmdate($date_format, $timestamp);    
       
       return $global_timestamp;
   }
}