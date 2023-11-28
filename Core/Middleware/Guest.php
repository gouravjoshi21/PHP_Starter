<?php
namespace Core\Middleware;

use Core\Response;

class Guest {
    public function handle ($api) {
        
        if (!$GLOBALS['AuthObj']->isLoggedIn()) return;

        if ($api) 
            response ([
                'code' => Response::BAD_REQUEST,
                'msg' => 'Only Guests are allowed!'
            ]);

        else header('location: /');

        exit();
    }
}