<?php

abstract class UnitOfWorkAbstract
{
    protected $_newEntities = array();
    protected $_cleanEntities = array();
    protected $_dirtyEntities = array();
    protected $_removedEntities = array();
    protected $_dataMapper;

    /**
     * Class constructor
     */
    public function __construct(DataMapperAbstract $dataMapper)
    {
        $this->_dataMapper = $dataMapper;
    }

    /**
     * Get the data mapper the Unit of Work uses
     */
    public function getDataMapper()
    {
        return $this->_dataMapper;
    }

    /**
     * Mark an entity 'new'
     */
    public function markNew(EntityAbstract $entity)
    {
        $id = $entity->id;
        if ($id !== null) {
            if (array_key_exists($id, $this->_cleanEntities)) {
                unset($this->_cleanEntities[$id]);
            }
            if (array_key_exists($id, $this->_dirtyEntities)) {
                unset($this->_dirtyEntities[$id]);
            }
            if (array_key_exists($id, $this->_removedEntities)) {
                unset($this->_removedEntities[$id]);
            }
        }
        if (!in_array($entity, $this->_newEntities, true)) {
            $this->_newEntities[] = $entity;
        }
    }

    /**
     * Remove an entity previously marked 'new'
     */
    protected function _removeNew(EntityAbstract $entity)
    {
        if (in_array($entity, $this->_newEntities, true)) {
            $newEntities = array();
            foreach ($this->_newEntities as $_newEntity) {
                if ($entity !== $_newEntity) {
                    $newEntities[] = $_newEntity;
                }
            }
            $this->_newEntities = $newEntities;
        }
    }

    /**
     * Get all the 'new' entities
     */
    public function getNewEntities()
    {
        return $this->_newEntities;
    }

    /**
     * Mark an entity 'clean'
     */
    public function markClean(EntityAbstract $entity)
    {
        $this->_removeNew($entity);
        $id = $entity->id;
        if ($id !== null) {
            if (array_key_exists($id, $this->_dirtyEntities)) {
                unset($this->_dirtyEntities[$id]);
            }
            if (array_key_exists($id, $this->_removedEntities)) {
                unset($this->_removedEntities[$id]);
            }
            if (!array_key_exists($id, $this->_cleanEntities)) {
                $this->_cleanEntities[$id] = $entity;
            }
        }
    }

    /**
     * Get all the 'clean' entities
     */
    public function getCleanEntities()
    {
        return $this->_cleanEntities;
    }

    /**
     * Mark an entity 'dirty'
     */
    public function markDirty(EntityAbstract $entity)
    {
        $this->_removeNew($entity);
        $id = $entity->id;
        if ($id !== null) {
            if (array_key_exists($id, $this->_cleanEntities)) {
                unset($this->_cleanEntities[$id]);
            }
            if (array_key_exists($id, $this->_removedEntities)) {
                unset($this->_removedEntities[$id]);
            }
            if (!array_key_exists($id, $this->_dirtyEntities)) {
                $this->_dirtyEntities[$id] = $entity;
            }
        }
    }

    /**
     * Get all the 'dirty' entities
     */
    public function getDirtyEntities()
    {
        return $this->_dirtyEntities;
    }

    /**
     * Mark an entity 'removed'
     */
    public function markRemoved(EntityAbstract $entity)
    {
        $this->_removeNew($entity);
        $id = $entity->id;
        if ($id !== null) {
            if (array_key_exists($id, $this->_cleanEntities)) {
                unset($this->_cleanEntities[$id]);
            }
            if (array_key_exists($id, $this->_dirtyEntities)) {
                unset($this->_dirtyEntities[$id]);
            }
            if (!array_key_exists($id, $this->_removedEntities)) {
                $this->_removedEntities[$id] = $entity;
            }
        }
    }

    /**
     * Get all the 'removed' entities
     */
    public function getRemovedEntities()
    {
        return $this->_removedEntities;
    }

    /**
     * Clear all the 'new' entities
     */
    public function clearNew()
    {
        $this->_newEntities = array();
        return $this;
    }

    /**
     * Clear all the 'clean' entities
     */
    public function clearClean()
    {
        $this->_cleanEntities = array();
        return $this;
    }

    /**
     * Clear all the 'dirty' entities
     */
    public function clearDirty()
    {
        $this->_dirtyEntities = array();
        return $this;
    }

    /**
     * Clear all the 'removed' entities
     */
    public function clearRemoved()
    {
        $this->_removedEntities = array();
        return $this;
    }

    /**
     * Clear all the entities stored in the Unit Of Work
     */
    public function clearAll()
    {
        $this->clearNew()
            ->clearClean()
            ->clearDirty()
            ->clearRemoved();
    }

    /**
     * Find an entity by its ID (implements an identity map)
     */
    public function findById($id)
    {
        if (array_key_exists($id, $this->_cleanEntities)) {
            return $this->_cleanEntities[$id];
        }
        if ($entity = $this->_dataMapper->findById($id)) {
            $this->markClean($entity);
            return $entity;
        }
        return null;
    }

    /**
     * Find all the entities
     */
    public function findAll()
    {
        $collection = $this->_dataMapper->findAll();
        if ($collection !== null) {
            foreach ($collection as $entity) {
                $this->markClean($entity);
            }
            return $collection;
        }
        return null;
    }

    /**
     * Commit all the pending entity operations in one go (insert, update, delete)
     */
    public function commit()
    {
        // save all the 'new' entities
        if (!empty($this->_newEntities)) {
            foreach ($this->_newEntities as $_newEntity) {
                $this->_dataMapper->insert($_newEntity);
            }
        }
        // update all the 'dirty' entities
        if (!empty($this->_dirtyEntities)) {
            foreach ($this->_dirtyEntities as $_dirtyEntity){
                $this->_dataMapper->update($_dirtyEntity);
            }
        }
        // delete all the 'removed' 'entities
        if (!empty($this->_removedEntities)) {
            foreach ($this->_removedEntities as $_removedEntity) {
                $this->_dataMapper->delete($_removedEntity);
            }
        }
    }
}