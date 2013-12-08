<?php
namespace Burrito\Framework;

use Burrito\Framework\Http\Request;

class ControllerResolver {

    // return string with name of the controller
    public function getController(Request $request){
        $route = $request->getRoute();
        $controller = $route['controller'];
        $action = $route['action'];
        list($module, $controller) = explode('::', $controller);
        $controllerFile = $module.'\Controller\\'.$controller.'Controller';
        $controller = new $controllerFile($request, $this);
        return array($controller, $action.'Action');
    }

} 