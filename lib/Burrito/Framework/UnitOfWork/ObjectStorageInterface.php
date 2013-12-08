<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:30 AM
 */

namespace ModelRepository;


interface ObjectStorageInterface extends Countable, Iterator, ArrayAccess
{
    public function attach($object, $data = null);
    public function detach($object);
    public function clear();
}