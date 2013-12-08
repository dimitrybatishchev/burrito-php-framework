<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 8:23 PM
 */

namespace Burrito\Framework\UOW;


abstract class EntityAbstract
{
    protected $_values = array();
    protected $_allowedFields = array();

    /**
     * Class constructor
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Assign a value to the specified field via the corresponding mutator (if it exists);
     * otherwise, assign the value directly to the '$_values' protected array
     */
    public function __set($name, $value)
    {
        if (!in_array($name, $this->_allowedFields)) {
            throw new EntityException('The field ' . $name . ' is not allowed for this entity.');
        }
        $mutator = 'set' . ucfirst($name);
        if (method_exists($this, $mutator) && is_callable(array($this, $mutator))) {
            $this->$mutator($value);
        }
        else {
            $this->_values[$name] = $value;
        }
    }

    /**
     * Get the value assigned to the specified field via the corresponding getter (if it exists);
    otherwise, get the value directly from the '$_values' protected array
     */
    public function __get($name)
    {
        if (!in_array($name, $this->_allowedFields)) {
            throw new EntityException('The field ' . $name . ' is not allowed for this entity.');
        }
        $accessor = 'get' . ucfirst($name);
        if (method_exists($this, $accessor) && is_callable(array($this, $accessor))) {
            return $this->$accessor;
        }
        return array_key_exists($name, $this->_values) ?
            $this->_values[$name] :
            null;
    }

    /**
     * Get an associative array with the values assigned to the fields of the entity
     */
    public function toArray()
    {
        return $this->_values;
    }
}