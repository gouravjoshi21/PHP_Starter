<?php
namespace Core;

use Core\Middleware\Middleware;

use Exception;

class Router {
    protected $routes = [];

    
    public function add ($method, $uri, $controller, $api) {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null,
            'api' => $api
        ];

        return $this;
    }

    public function get ($uri, $controller, $api = false) {
        return $this->add ('GET', $uri, $controller, $api);
    }

    public function post ($uri, $controller, $api = false) {
        return $this->add ('POST', $uri, $controller, $api);
    }

    public function delete ($uri, $controller, $api = false) {
        return $this->add ('DELETE', $uri, $controller, $api);
    }

    public function patch ($uri, $controller, $api = false) {
        return $this->add ('PATCH', $uri, $controller, $api);
    }

    public function put ($uri, $controller, $api = false) {
        return $this->add ('PUT', $uri, $controller, $api);
    }

    public function only ($key) {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }
    

    public function route ($uri, $method) {
        foreach($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                if ($route['api']) {
                    try {
                        Middleware::resolve($route['middleware'], true);

                        require_once base_path($route['controller']);
    
                        $errorCode = 400;
                        throw new Exception('Bad request!');
                    } catch (Exception $ex) {
                        $errorCode = empty($errorCode) ? 500 : $errorCode;
                        
                        response (["code" => $errorCode, "msg" => $ex->getMessage()]);
                        exit();
                    }
                } else {
                    Middleware::resolve($route['middleware']);
    
                    return require base_path($route['controller']);
                }

            }
        }

        // Abort
        $this->abort();
    }

    protected function abort ($code = 404) {
        http_response_code ();

        return require base_path("views/$code.php");

        die();
    }
}