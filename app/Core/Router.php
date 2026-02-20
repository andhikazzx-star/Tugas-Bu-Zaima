<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function add($method, $path, $handler)
    {
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $path);
        $path = "#^" . $path . "$#";
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get($path, $handler)
    {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch($method, $uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = str_replace('/EDUTEN2', '', $uri);
        if ($uri == '')
            $uri = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $uri, $matches)) {
                $handler = $route['handler'];
                if (is_array($handler)) {
                    $controller = new $handler[0]();
                    $action = $handler[1];

                    $params = array_filter($matches, function ($key) {
                        return !is_numeric($key);
                    }, ARRAY_FILTER_USE_KEY);

                    return $controller->$action($params);
                }
                return call_user_func($handler);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
