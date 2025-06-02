<?php
class Router {
    private $routes;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->routes = require_once 'config/routes.php';
    }

    public function dispatch($action) {
        if (!isset($this->routes[$action])) {
            // Route par défaut
            include 'views/home.php';
            return;
        }

        $route = $this->routes[$action];
        $controllerName = $route['controller'];
        $methodName = $route['method'];

        $controller = new $controllerName($this->db);
        $controller->$methodName();
    }
}
