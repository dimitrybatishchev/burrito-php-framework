<?php

namespace Burrito\Framework;

use Burrito\Framework\ServiceContainer as ServiceContainer;
use Burrito\Framework\EventDispatcher\EventDispatcher as EventDispatcher;
use Burrito\Framework\Http\Request as Request;
use Burrito\Framework\ControllerResolver as ControllerResolver;
use Burrito\Framework\Routing\Router as Router;
use Burrito\Framework\UserManagment\SecurityManager;
use Burrito\Framework\Model\EntityManager;

class Application{

    public function __construct(){

    }

    public function run(){
        $request = new Request();

        $serviceContainer = new ServiceContainer();

        $serviceContainer['security'] = function(){
            return new SecurityManager();
        };
        $serviceContainer['dispatcher'] = function(){
            return new EventDispatcher();
        };
        $serviceContainer['request'] = function() use ($request){
            return $request;
        };
        $serviceContainer['entityManager'] = function(){
            $schema = include(ROOT.'\app\config\schema.php');
            $entityManager = new EntityManager(new \PDO("mysql:dbname=framework", "admin", "admin", array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')), $schema);
            return $entityManager;
        };

        $routes = ROOT.'\app\config\routes.php';
        $matcher = new Router($routes);

        $request->setRoute($matcher->match($request));

        $controllerResolver = new ControllerResolver();
        list($controller, $action) = $controllerResolver->getController($request);
        $controller->setContainer($serviceContainer);

        $response = call_user_func_array(array($controller, $action), $request->getParams());

        $response->send();
    }

}