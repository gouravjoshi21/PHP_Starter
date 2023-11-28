<?php
namespace Core\Middleware;

use Core\Response;

class Admin {
    public function handle ($api) {

        if ($GLOBALS['AuthObj']->isLoggedIn() && $GLOBALS['AuthObj']->isAdmin()) return;

        if ($api) 
            response ([
                'code' => Response::BAD_REQUEST,
                'msg' => 'You are not admin!'
            ]);

        else header('location: /login.php');

        exit();
    }
}