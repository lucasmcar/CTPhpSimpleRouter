<?php

namespace App;

class Router
{
    private array $handlers;
    private $notFoundHandler;

    public function get($route, $handler)
    {
        $this->addHandler('GET' ,$route,$handler);
    }

    public function post($route, $handler)
    {
        $this->addHandler('POST', $route, $handler);
    }

    public function addNotFoundPage($handler)
    {
        $this->notFoundHandler = $handler;
    }

    private function addHandler($method, $route, $action)
    {
        $this->handlers[$method.$route] = [
            'path' => $route,
            'method' => $method,
            'action' => $action
        ];
    }

    public function run()
    {
        $requestUri  = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = null;
        foreach($this->handlers as $handler){
            if($handler['path'] === $requestPath && $method === $handler['method']){
                $callback = $handler['handler'];
            }
        }

        if(is_string($callback)){
            $arrayCallback = explode('@', $callback);
            if(is_array($arrayCallback)){
                $className = array_shift($arrayCallback);
                $handler = new $className;
                $method = array_shift($arrayCallback);

                $callback = [$handler, $method];
            }
        }

        if(!$callback){
            header("HTTP/1.0 404 Not found");
            if(!empty($this->notFoundHandler)){
                $callback = $this->notFoundHandler;
            }
            return;
        }

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }
}