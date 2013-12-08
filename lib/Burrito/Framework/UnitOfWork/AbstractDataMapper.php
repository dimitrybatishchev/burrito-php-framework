<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:32 AM
 */

namespace ModelRepository;


use LibraryDatabaseDatabaseAdapterInterface,
    ModelCollectionEntityCollectionInterface,
    ModelEntityInterface;

abstract class AbstractDataMapper implements DataMapperInterface
{
    protected $adapter;
    protected $collection;
    protected $entityTable;

    public function __construct(DatabaseAdapterInterface $adapter, EntityCollectionInterface $collection, $entityTable = null) {
        $this->adapter = $adapter;
        $this->collection = $collection;
        if ($entityTable !== null) {
            $this->setEntityTable($entityTable);
        }
    }

    public function setEntityTable($entityTable) {
        if (!is_string($table) || empty($entityTable)) {
            throw new InvalidArgumentException(
                "The entity table is invalid.");
        }
        $this->entityTable = $entityTable;
        return $this;
    }

    public function fetchById($id) {
        $this->adapter->select($this->entityTable,
            array("id" => $id));
        if (!$row = $this->adapter->fetch()) {
            return null;
        }
        return $this->loadEntity($row);
    }

    public function fetchAll(array $conditions = array()) {
        $this->adapter->select($this->entityTable, $conditions);
        $rows = $this->adapter->fetchAll();
        return $this->loadEntityCollection($rows);
    }

    public function insert(EntityInterface $entity) {
        return $this->adapter->insert($this->entityTable,
            $entity->toArray());
    }

    public function update(EntityInterface $entity) {
        return $this->adapter->update($this->entityTable,
            $entity->toArray(), "id = $entity->id");
    }

    public function save(EntityInterface $entity) {
        return !isset($entity->id)
            ? $this->adapter->insert($this->entityTable,
                $entity->toArray())
            : $this->adapter->update($this->entityTable,
                $entity->toArray(), "id = $entity->id");
    }

    public function delete(EntityInterface $entity) {
        return $this->adapter->delete($this->entityTable,
            "id = $entity->id");
    }

    protected function loadEntityCollection(array $rows) {
        $this->collection->clear();
        foreach ($rows as $row) {
            $this->collection[] = $this->loadEntity($row);
        }
        return $this->collection;
    }

    abstract protected function loadEntity(array $row);
}