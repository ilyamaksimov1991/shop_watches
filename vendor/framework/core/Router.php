<?php
namespace framework;

use framework\base\AbstractController;

/**
 * Class Router
 *
 * @package framework
 */
class Router
{
    /**
     * @var array $routes
     */
    private static $routes = [];
    /**
     * @var array $route
     */
    private static $route = [];

    /**
     * Adding Routing Rules
     *
     * @param string $regex
     * @param array $route
     */
    public static function add($regex, $route = [])
    {
        self::$routes[$regex] = $route;
    }

    /**
     *  Get all routing rules
     *
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * Get route
     *
     * @return array
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * Connecting the controller and the action on the requested url
     *
     * @param string $url
     * @throws \Exception
     */
    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['prefix'] . self::upperCamelCase(self::$route['controller']) . 'Controller';

            if (class_exists($controller)) {
                $controllerObject = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($controllerObject, $action)) {
                    $controllerObject->$action();
                    /**
                     * @var AbstractController $controllerObject
                     */
                    $controllerObject->getView();
                } else {
                    throw new \Exception("В контроллере {$controller}::<b>{$action}</b> не существует", 404);
                }
            } else {
                throw new \Exception("Контроллер {$controller} не существует", 404);
            }
        } else {
            throw new \Exception('Страница не найдена', 404);
        }

    }

    /**
     * Finding the client's request path in routs
     *
     * @param string $url
     * @return bool
     */
    private static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#{$pattern}#i", $url, $math)) {

                foreach ($math as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }

                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }

                if (!isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }

                self::$route = $route;

                return true;
            }

        }
        return false;
    }

    /**
     * Translate the first letter in each word to uppercase.
     *
     * @param string $name
     * @return mixed
     */
    private static function upperCamelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('-', " ", $name)));
    }

    /**
     * Translate the first letter to lower case.
     *
     * @param string $name
     * @return string
     */
    private static function lowerCamelCase($name)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', " ", $name))));
    }

    /**
     * Remove Get Parameters
     *
     * @param string $url
     * @return string
     */
    private static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }

    }

}