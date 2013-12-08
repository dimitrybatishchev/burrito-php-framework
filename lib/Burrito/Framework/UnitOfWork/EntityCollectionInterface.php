<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:37 AM
 */

namespace ModelRepository;


interface EntityCollectionInterface extends Countable, ArrayAccess, IteratorAggregate
{
    public function add(EntityInterface $entity);
    public function remove(EntityInterface $entity);
    public function get($key);
    public function exists($key);
    public function clear();
    public function toArray();
}