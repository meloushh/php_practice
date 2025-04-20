<?php

require_once 'Request.php';
require_once 'Response.php';
require_once 'functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Route {
    public array $methods;
    public string $uri;
    public array $handler;
    
    function __construct(array $methods, string $uri, array $handler) {
        $this->methods = $methods;
        $this->uri = $uri;
        $this->handler = $handler;
    }
}

class App {
    public Request $request;
    public array $routes;

    function __construct(array $routes) {
        $this->routes = $routes;
    }

    function HandleRequest() {
        $this->request = new Request();
        $this->request->method = $_SERVER['REQUEST_METHOD'];
        $this->request->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->request->headers = getallheaders();
        $this->request->getParams = $_GET;
        $this->request->postParams = $_POST;
        $this->request->protocol = str_contains($_SERVER['SERVER_PROTOCOL'], 'HTTP/') ? Protocol::HTTP : Protocol::HTTPS;
        $this->request->body = file_get_contents('php://input');

        // routing
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route->methods, $route->uri, $route->handler);
            }
        });

        $routeInfo = $dispatcher->dispatch($this->request->method, $this->request->uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                DumpDie(404);
                break;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                DumpDie(405);
                break;

            case \FastRoute\Dispatcher::FOUND:
                $controller = new $routeInfo[1][0];
                call_user_func_array([$controller, $routeInfo[1][1]], []);
                break;
        }
    }
}

?>