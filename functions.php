<?php
use Core\Response;


function dd ($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs ($value) {
    return $GLOBALS['uri'] === $value;
}

function abort ($code = 404) {
    http_response_code ();

    return require base_path("views/$code.php");

    die();
}

function authorize($condition, $status = Response::FORBIDDEN) {
    if (!$condition)  abort($status);
}

function base_path ($path) {
    return BASE_PATH . $path;
}

function view ($path, $attributes = []) {
    extract($attributes);
    
    require base_path('views/'.$path);
}

function response ($arr) {
    $responseArr = [];

    $code = isset($arr['code']) ? $arr['code'] : 200; 

    $responseArr['status'] = $code == 200 ? 'success' : 'fail';

    if (isset($arr['msg'])) {
        $responseArr['message'] = $arr['msg'];
    }

    if (isset($arr['data'])) {
        $responseArr['data'] = $arr['data'];
    }

    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($responseArr);

    if ($arr['die'] ?? false) die();
}