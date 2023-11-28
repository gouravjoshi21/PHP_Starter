<?php 
namespace Core;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

use Core\Database;

use UnexpectedValueException;

class Auth {
    private $token = '';
    public $loggedIn = false;
    public $decodedData;
    public $role;

    private $secret;

    public function __construct () {
        $this->env = $GLOBALS['configObj'];

        if (!empty($_COOKIE['token'])) {
            $this->token = htmlspecialchars($_COOKIE['token']);
        }

        if (!empty($_GET['token'])) {
            $this->token = htmlspecialchars($_GET['token']);
        }

        try {
            $this->decodedData = JWT::decode($this->token, new Key($this->env->get('JWT_SECRET'), 'HS512'));

            if (isset($this->decodedData->data)) {
                $db = new Database();

                $data = $db->query('Select role from user where id = :id', ['id' => $this->decodedData->data->id])->find();

                $this->role = isset($data['role']) ? $data['role'] : 'user';
                
                $this->loggedIn = true;
            }

            $this->loggedIn = true;
        } catch (UnexpectedValueException $ex) { $this->loggedIn = false; }
        catch (Exception $ex) { $this->loggedIn = false;}
    }

    public function isLoggedIn () {
        return $this->loggedIn;
    }

    public function isAdmin () {
        return $this->role == 'admin' ? 1 : 0;
    }

    public function role () {
        return $this->role;
    }

    public function getData ($field = '') {
        if ($field ?? false) return $this->decodedData->data->{$field} ?? '';

        return $this->decodedData;
    }

    public function generateJWT ($data) {
        $iss = "localhost";
        $iat = time();
        $nbf = $iat;
        $exp = $iat + (86400 * $this->env->get("JWT_EXPIRES_IN"));
        $aud = "myusers";
        
        $payloadInfo = array (
            "iss" => $iss,
            "iat" => $iat,
            "nbf" => $nbf,
            "exp" => $exp,
            "aud" => $aud,
            "data" => $data
        );

        $jwt = JWT::encode($payloadInfo, $this->env->get("JWT_SECRET"), 'HS512');

        return $jwt;
    }
}