<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 8:25 PM
 */

namespace Burrito\Framework\UOW;

abstract class CollectionAbstract implements Iterator, Countable, ArrayAccess
{
    protected $_entities = array();

    /**
     * Get the entities stored in the collection
     */
    public function getEntities()
    {
        return $this->_entities;
    }

    /**
     * Clear the collection of entities
     */
    public function clear()
    {
        $this->_entities = array();
    }

    /**
     * Reset the collection of entities (implementation required by Iterator Interface)
     */
    public function rewind()
    {
        reset($this->_entities);
    }

    /**
     * Get the current entity in the collection (implementation required by Iterator Interface)
     */
    public function current()
    {
        return current($this->_entities);
    }

    /**
     * Move to the next entity in the collection (implementation required by Iterator Interface)
     */
    public function next()
    {
        next($this->_entities);
    }

    /**
     * Get the key of the current entity in the collection (implementation required by Iterator Interface)
     */
    public function key()
    {
        return key($this->_entities);
    }

    /**
     * Check if there are more entities in the collection (implementation required by Iterator Interface)
     */
    public function valid()
    {
        return (boolean) $this->current();
    }

    /**
     * Count the number of entities in the collection (implementation required by Countable Interface)
     */
    public function count()
    {
        return count($this->_entities);
    }

    /**
     * Add an entity to the collection (implementation required by ArrayAccess interface)
     */
    public function offsetSet($key, $entity)
    {
        if ($key === null) {
            if (!in_array($key, $this->_entities, true)) {
                $this->_entities[] = $entity;
                return;
            }
        }
        else if (!array_key_exists($key, $this->_entities)) {
            $this->_entities[$key] = $entity;
        }
    }

    /**
     * Remove an entity from the collection (implementation required by ArrayAccess interface)
     */
    public function offsetUnset($key)
    {
        if ($key instanceof EntityAbstract) {
            $entities = array();
            foreach ($this->_entities as $_entity) {
                if ($_entity !== $key) {
                    $entities[] = $_entity;
                }
            }
            $this->_entities = $entities;
            return;
        }
        if (array_key_exists($key, $this->_entities)) {
            unset($this->_entities[$key]);
        }
    }

    /**
     * Get the specified entity from the collection (implementation required by ArrayAccess interface)
     */
    public function offsetGet($key)
    {
        return array_key_exists($key, $this->_entities) ?
            $this->_entities[$key] :
            null;
    }

    /**
     * Check if the specified entity exists in the collection (implementation required by ArrayAccess interface)
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->_entities);
    }
}