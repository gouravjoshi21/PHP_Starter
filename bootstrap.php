<?php

use Core\App;
use Core\Container;
use Core\Database;

$container = new Container ();

$container->bind('Core\Database', function () {
    require_once base_path('vendor/autoload.php');
    
    return new Database();
});

App::setContainer ($container);