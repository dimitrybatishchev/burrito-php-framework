<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/30/13
 * Time: 6:18 PM
 */

namespace Burrito\Framework\Model;


class Collection implements \Countable, \Iterator, \ArrayAccess {

    protected $entityManager;
    protected $targetEntity;
    protected $mappedBy;

    protected $data = array();
    private $loaded = false;
    private $pos = 0;
    private $container;

    public function __construct($targetEntity, $mappedBy, $entityManager, $container){
        $this->targetEntity = $targetEntity;
        $this->mappedBy = $mappedBy;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function load(){
        $this->data = $this->entityManager->fetchCollection($this->targetEntity, $this->container);
        $this->loaded = true;
    }

    public function getItem($key){
        if (!$this->loaded) $this->load();
        return $this->data[$key];
    }

    public function count() {
        if (!$this->loaded) $this->load();
        return sizeof($this->data);
    }

    public function rewind() {
        $this->pos = 0;
    }

    public function valid() {
        return $this->pos < sizeof($this);
    }

    public function key() {
        return $this->pos;
    }

    public function current() {
        return $this->getItem($this->pos);
    }

    public function next() {
        $this->pos++;
    }


    /**
     * ARRAYACCESS
     */

    public function offsetSet($offset, $value) {
        throw new Exception("Cannot add to Collection.");
    }

    public function offsetExists($offset) {
        return ($offset < sizeof($this) && $offset >= 0 ? true : false);
    }

    public function offsetUnset($offset) {
        $this->getItem($offset)->delete();
        unset($this->keys[$offset]);
    }

    public function offsetGet($offset) {
        return $this->getItem($offset);
    }

}