<?php
const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . "functions.php";
require_once base_path('config.php');

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $classPath = str_contains($class, 'Core') ? "{$class}.php" : "Core/{$class}.php";

    require base_path($classPath);
});

require_once base_path('vendor/autoload.php');
require base_path('bootstrap.php');


$AuthObj = new \Core\Auth();

$router = new \Core\Router();


require base_path('routes/index.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);