<?php

$ENV = 'DEV'; 

switch ($ENV) {
    case 'DEV':
        $path = BASE_PATH.'env.php';

        break;

    case 'PROD':
        $path = BASE_PATH.'../config/thetypingworld.php';

        break;
    default:
        $path = BASE_PATH.'env.php';
}

require_once $path;