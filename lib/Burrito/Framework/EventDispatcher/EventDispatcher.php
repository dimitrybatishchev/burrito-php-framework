<?php

namespace Burrito\Framework\EventDispatcher;

class EventDispatcher{

    private $events;

    public function __construct(){
        $events = array();
    }

    public function addListener($event, $action){
        if (!$this->events[$event]){
            $this->events[$event] = array();
        }
        array_push($this->events[$event], $action);
    }

    public function dispatch($event){
        foreach ($this->events[$event] as $action){
            $action();
        }
    }

}