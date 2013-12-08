<?php
namespace Burrito\Framework;

use Burrito\Framework\Http\Request;

class Controller {
    protected $view;
    protected $request;
    protected $router;
    protected $container;

    function __construct(Request $request){
        $route = $request->getRoute();
        $controller = $route['controller'];
        list($module) = explode('::', $controller);
        $this->view = new View($module);

        $this->request = $request;
    }

    public function setContainer($container){
        $this->container = $container;
    }

    public function getContainer(){
        return $this->container;
    }

    public function redirect($routeName){
        $this->router->redirectByName($routeName);
    }

}