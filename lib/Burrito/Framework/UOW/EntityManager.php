<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 8:27 PM
 */

namespace Burrito\Framework\UOW;


class EntityManager
{
    protected $_unitOfWorkFactory;
    protected $_unitOfWorks = array();
    protected $_currentUnitOfWork;

    /**
     * Class constructor
     */
    public function __construct(UnitOfWorkFactory $unitOfWorkFactory)
    {
        $this->_unitOfWorkFactory = $unitOfWorkFactory;
        $this->init();
    }

    /**
     * Initialize the entity manager here
     */
    public function init()
    {
        $this->addUnitOfWork('user');
    }

    /**
     * Get the Unit of Work factory
     */
    public function getUnitOfWorkFactory()
    {
        return $this->_unitOfWorkFactory;
    }

    /**
     * Add a new Unit of Work by specifying its key
     */
    public function addUnitOfWork($key)
    {
        $key = strtolower($key);
        if (!array_key_exists($key, $this->_unitOfWorks)) {
            $unitOfWork = $this->_unitOfWorkFactory->create($key);
            $this->_unitOfWorks[$key] = $unitOfWork;
            $this->setCurrentUnitOfWork($key);
        }
        return $this;
    }

    /**
     * Add multiple Units of Work by specifying an array of keys
     */
    public function addUnitOfWorks(array $keys)
    {
        if (!empty($keys)) {
            foreach ($keys as $key) {
                $this->addUnitOfWork($key);
            }
        }
        return $this;
    }

    /**
     * Get all the Units of Work
     */
    public function getUnitOfWorks()
    {
        return $this->_unitOfWorks;
    }

    /**
     * Set the current Unit of Work
     */
    public function setCurrentUnitOfWork($key)
    {
        $key = strtolower($key);
        if (!array_key_exists($key, $this->_unitOfWorks)) {
            throw new EntityManagerException('The specified Unit of Work is not valid.');
        }
        $this->_currentUnitOfWork = $this->_unitOfWorks[$key];
        return $this;
    }

    /**
     * Get the current Unit of Work
     */
    public function getCurrentUnitOfWork()
    {
        if ($this->_currentUnitOfWork === null) {
            throw new EntityManagerException('No current Unit of Work has been set yet.');
        }
        return $this->_currentUnitOfWork;
    }

    /**
     * Set the specified entity 'new'
     */
    public function setNew(EntityAbstract $entity)
    {
        $this->getCurrentUnitOfWork()->setNew($entity);
    }

    /**
     * Set the specified entity 'clean'
     */
    public function setClean(EntityAbstract $entity)
    {
        $this->getCurrentUnitOfWork()->setClean($entity);
    }

    /**
     * Set the specified entity 'dirty'
     */
    public function setDirty(EntityAbstract $entity)
    {
        $this->getCurrentUnitOfWork()->setDirty($entity);
    }

    /**
     * Set the specified entity 'removed'
     */
    public function setRemoved(EntityAbstract $entity)
    {
        $this->getCurrentUnitOfWork()->setRemoved($entity);
    }

    /**
     * Clear the 'new' entities stored in the current Unit of Work
     */
    public function clearNew()
    {
        $this->getCurrentUnitOfWork()->clearNew();
    }

    /**
     * Clear the 'clean' entities stored in the current Unit of Work
     */
    public function clearClean()
    {
        $this->getCurrentUnitOfWork()->clearClean();
    }

    /**
     * Clear the 'dirty' entities stored in the current Unit of Work
     */
    public function clearDirty()
    {
        $this->getCurrentUnitOfWork()->clearDirty();
    }

    /**
     * Clear the 'removed' entities stored in the current Unit of Work
     */
    public function clearRemoved()
    {
        $this->getCurrentUnitOfWork()->clearRemoved();
    }

    /**
     * Clear the entities stored in all the Units of Work
     */
    public function clearAll()
    {
        if (!empty($this->_unitOfWorks)) {
            foreach ($this->_unitOfWorks as $unitOfWork) {
                $unitOfWork->clearAll();
            }
        }
    }

    /**
     * Find an entity by its ID
     */
    public function findById($id)
    {
        return $this->getCurrentUnitOfWork()->findById($id);
    }

    /**
     * Find all the entities
     */
    public function findAll()
    {
        return $this->getCurrentUnitOfWork()->findAll();
    }

    /**
     * Commit all the pending entity operations (create, update, remove)
     */
    public function commit()
    {
        if (!empty($this->_unitOfWorks)) {
            foreach ($this->_unitOfWorks as $unitOfWork) {
                $unitOfWork->commit();
            }
        }
    }
}