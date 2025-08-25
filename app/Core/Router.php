<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add($httpMethod, $path, $action, $options = [])
    {
        $this->routes[$httpMethod][$path] = [
            'action'     => $action,
            'name'       => $options['name'] ?? null,
            'middleware' => $options['middleware'] ?? []
        ];
    }

    // Shortcut cho từng method
    public function get($path, $action, $options = [])
    {
        $this->add('GET', $path, $action, $options);
    }
    public function post($path, $action, $options = [])
    {
        $this->add('POST', $path, $action, $options);
    }
    public function put($path, $action, $options = [])
    {
        $this->add('PUT', $path, $action, $options);
    }
    public function delete($path, $action, $options = [])
    {
        $this->add('DELETE', $path, $action, $options);
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $path => $route) {
            // Chuyển /user/{id} thành regex
            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $path);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // bỏ full match
                $params = $matches;

                // Middleware
                foreach ($route['middleware'] as $mw) {
                    if (is_callable($mw)) {
                        // Nếu là hàm callback/closure thì gọi trực tiếp
                        $result = $mw();
                        if ($result === false) {
                            return; // nếu middleware return false thì dừng
                        }
                    } elseif (is_array($mw)) {
                        // Ví dụ: ['PermissionMiddleware', 'view_users']
                        $mwClass = "App\\Middleware\\" . $mw[0];
                        (new $mwClass($mw[1]))->handle();
                    } else {
                        $mwClass = "App\\Middleware\\$mw";
                        (new $mwClass())->handle();
                    }
                }

                // Controller
                [$controller, $methodName] = explode('@', $route['action']);
                $controllerClass = "App\\Controllers\\$controller";
                $ctrl = new $controllerClass();

                return $ctrl->$methodName(...$params);
            }
        }

        header("Location: /404");
        exit;
    }

    public function route($name, $params = [])
    {
        foreach ($this->routes as $methodRoutes) {
            foreach ($methodRoutes as $path => $info) {
                if ($info['name'] === $name) {
                    $url = $path;

                    // Thay thế các {param} trong path bằng giá trị từ $params
                    foreach ($params as $key => $value) {
                        $url = preg_replace('/\{' . $key . '\}/', $value, $url);
                    }

                    return $url;
                }
            }
        }
        return null;
    }
}
