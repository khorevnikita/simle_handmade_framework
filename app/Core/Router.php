<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 07.11.2019
 * Time: 10:58
 */

namespace App;

class Router
{
    static $controllerNamespace = "App\Controllers\\";

    public static function start()
    {

        $request_uri = explode("?", $_SERVER['REQUEST_URI'])[0];
        $web_routes = WebRoutes::$web;

        foreach ($web_routes as $uri => $route) {
            $compare = self::compare(trim($request_uri, '/'), trim($uri, '/'));
            if ($compare) {
                if ($_SERVER['REQUEST_METHOD'] == strtoupper($route['method']) || strtoupper($route['method']) == "ANY") {
                    $path = $route['path'];
                    break;
                }
            }
        }
        $routeCompleted = false;
        // Looking for a path in WebRoutes class
        if (isset($path)) {
            $controllerName = explode("@", $path)[0];
            $controllerAction = explode("@", $path)[1];
            $routeCompleted = self::action($controllerName, $controllerAction, $compare);
        } else {
            // Looking for a resource system
            $routes = explode('/', $request_uri);

            if (isset($routes[1])) {
                $controllerType = ucfirst(rtrim(strtolower($routes[1]), 's'));
                $controllerName = $controllerType ?: "Home" . 'Controller';
                $controllerAction = "index";
                if (isset($routes[2])) {
                    $controllerAction = $routes[2];
                }
                $routeCompleted = self::action($controllerName, $controllerAction);
            }
        }
        if (!$routeCompleted) {
            return self::notFound();
        }
        return true;
    }

    protected static function action($controllerName, $controllerAction, $params = false)
    {
        $controllerPath = 'app/Controllers/' . $controllerName . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerName = self::$controllerNamespace . $controllerName;
            $controller = new $controllerName;
            if (method_exists($controller, $controllerAction)) {
                if (!is_array($params)) {
                    $params = [];
                }
                spl_autoload_register(function ($class_name) {
                    $path = "app/Models/" . explode('\\',$class_name)[count(explode('\\',$class_name)) - 1] . '.php';
                    include $path;
                });
                $controller->$controllerAction(...$params);
                return true;
            }
        }
        return false;
    }

    protected static function notFound()
    {
        $view = new View();
        return $view->render('404', []);
    }

    protected static function compare($requested, $url)
    {
        $direct = array_diff(explode("/", $requested), explode("/", $url));
        $renverser = array_diff(explode("/", $url), explode("/", $requested));
        if (!$direct && !$renverser) {

            return true;
        }
        if (array_keys($direct) == array_keys($renverser) && count($direct) > 1) {
            return $direct;
        }
        return false;

    }
}