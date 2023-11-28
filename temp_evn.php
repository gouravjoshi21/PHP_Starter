<?php
class Config {
    private $Env = [
        // DATABASE VARIABLES
        'HOSTNAME'=>'localhost',
        'DB_NAME'=>'',
        'USERNAME'=>'',
        'PASSWORD'=>'',

        // JWT CONFIG
        'JWT_SECRET'=>'generate_secret_key_of_36_char',
        'JWT_EXPIRES_IN'=>2,
        'JWT_COOKIE_EXPIRES_IN'=>2,

        // GOOGLE
        'CLIENT_ID' => '',
        'CLIENT_SECRET_KEY' => '',
        'GOOGLE_REDIRECT' => '',

        // ADMIN DETAILS
        'SMTP_HOST' => '',
        'SMTP_USERNAME' => '',
        'SMTP_PASSWORD' => '',
        'SMTP_PORT' => '',
        'SMPT_NAME' => '',
    ];

    public function get ($key) {

        return $this->Env[$key] ?? null;
    
    }
}

$configObj = new Config ();