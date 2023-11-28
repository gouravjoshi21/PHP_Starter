<?php
require base_path('api/index.php');

use \Core\User;

$UserObj = new User ($db->connection);

$Purifier->model = require base_path('model/user.php');


/********************************************************/

setcookie('token', 'loggedout',  time() + 1, '/');

response ([ 'msg' => 'User Loggged Out Successfully!']);

header("Location: /");
    
die();