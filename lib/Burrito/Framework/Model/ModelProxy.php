<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 12/2/13
 * Time: 8:24 AM
 */

namespace Burrito\Framework\Model;


class ModelProxy {

    private $container = null;
    private $instance = null;
    private $className = null;
    private $entityManager = null;
    private $id = null;

    public function __construct($className, $entityManager, $container, $id){
        $this->setClassName($className);
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->id = $id;
    }

    public function setClassName($className){
        if ($className !== null){
            $this->className = $className;
        }
    }

    public function setClassPath($classPath){
        if ($classPath !== null){
            $this->classPath = $classPath;
        }
    }

    public function getClassPath(){
        return $this->classPath;
    }

    public function getInstance(){
        if($this->instance === null){
            $this->instance = $this->initInstance();
        }
        return $this->instance;
    }

    public function initInstance(){
        return $this->entityManager->fetchObject($this->className, $this->container, $this->id);
    }

    public function __call($name, $arguments){
        $instance = $this->getInstance();
        return call_user_func_array(array($instance, $name), $arguments);
    }

    public function __get($name){
        return $this->getInstance()->$name;
    }

    public function __set($name, $value){
        $this->getInstance()->$name = $value;
    }

    public function __isset($name){
        return isset($this->getInstance()->$name);
    }

    public function __unset($name){
        unset($this->getInstance()->$name);
    }

} 