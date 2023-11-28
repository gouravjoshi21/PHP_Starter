<?php
use Core\App;
use Core\Purifier;

// SETUP URL PATH
$request = $_SERVER['REQUEST_URI'];
$routerArr = parse_url(str_replace('/api/v1', '', $request));
$uri = $routerArr['path'];
parse_str(isset($routerArr['query']) ? $routerArr['query'] : '', $getArr);

// STORE POST DATA AND DATA GET FROM URL
$getData = json_decode(json_encode($getArr));
$bodyData = json_decode(file_get_contents("php://input"));


// $db = App::resolve('Core\Database');

// $Purifier = new Purifier($bodyData);

$AuthObj = $GLOBALS['AuthObj'];