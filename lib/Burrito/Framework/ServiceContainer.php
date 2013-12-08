<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/22/13
 * Time: 4:16 PM
 */

namespace Burrito\Framework;


class ServiceContainer extends \ArrayObject{
    public function get($key){
        if (is_callable($this[$key])){
            return call_user_func($this[$key]);
        }

        throw new \RuntimeException("Cannot find service definition under the key [ $key ]");
    }
} 