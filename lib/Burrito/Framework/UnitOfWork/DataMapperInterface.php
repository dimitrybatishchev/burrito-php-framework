<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:31 AM
 */

namespace ModelRepository;

use ModelEntityInterface;

interface DataMapperInterface
{
    public function fetchById($id);
    public function fetchAll(array $conditions = array());
    public function insert(EntityInterface $entity);
    public function update(EntityInterface $entity);
    public function save(EntityInterface $entity);
    public function delete(EntityInterface $entity);
}