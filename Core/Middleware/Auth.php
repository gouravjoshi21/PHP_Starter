<?php
namespace Core\Middleware;

use Core\Response;

class Auth {
    public function handle ($api) {
        
        if ($GLOBALS['AuthObj']->isLoggedIn()) return;

        if ($api) 
            response ([
                'code' => Response::BAD_REQUEST,
                'msg' => 'You are not logged in!'
            ]);

        else header('location: /login.php');

        exit();
    }
}