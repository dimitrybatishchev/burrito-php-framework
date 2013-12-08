<?php
namespace Burrito\Framework\Routing;

use Burrito\Framework\Http\HttpRequest as HttpRequest;
use Burrito\Framework\Http\Request;

class Router {
    // Хранит конфигурацию маршрутов.
    private $routes;

    function __construct($routesPath){
        // Получаем конфигурацию из файла.
        $this->routes = include($routesPath);
    }

    // Метод получает URI. Несколько вариантов представлены для надёжности.
    function getURI(){
        /*
        if(!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
        if(!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }
        if(!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }*/
        return($_GET['r']);
    }

    public function match($request){
        // Получаем URI.
        $uri = $request->getPathInfo();

        // Пытаемся применить к нему правила из конфигуации.
        foreach($this->routes as $routeName => $route){
            // Если правило совпало.
            $uri = '/' . $uri;
            $pattern = $this->routes[$routeName]['url'];

            if(preg_match("~$pattern~", $uri)){

                preg_match("~$pattern~", $uri, $params);
                array_shift($params);
                $request->setParams($params);

                return $route;
            }
        }
    }

    function redirectByName($routeName){
        $pattern = $this->routes->routes[$routeName]->getPath();
        header('Location: ' . $pattern);
        exit();
    }
}