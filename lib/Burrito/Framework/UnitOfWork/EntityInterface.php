<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:37 AM
 */

namespace ModelRepository;


interface EntityInterface
{
    public function setField($name, $value);
    public function getField($name);
    public function fieldExists($name);
    public function removeField($name);
    public function toArray();
}