<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/29/13
 * Time: 7:54 AM
 */

namespace Burrito\Framework\Model;

class EntityManager {

    private $pdo;
    private $schema;

    public function __construct($pdo, $schema){
        $this->pdo = $pdo;
        $this->schema = $schema;
    }

    public function findAll($entityClass){
        $config = $this->schema[$entityClass];

        $resultArray = array();

        $function = new \ReflectionClass($entityClass);

        if ($config['table']){
            $tableName = $config['table'];
        } else {
            $tableName = $function->getShortName() . "s";
        }

        $sth = $this->pdo->query("SELECT * FROM `$tableName`");
        $sth->setFetchMode(\PDO::FETCH_ASSOC);

        while($row = $sth->fetch()){
            $entity = new $entityClass();
            foreach($config['columns'] as $key => $value){
                $objectMethod = 'set' . ucfirst ($key);
                if (method_exists($entityClass, $objectMethod)){
                    $entity->$objectMethod($row[$value]);
                }
            }
            foreach($config['relations'] as $key => $value){
                if ($config['relations'][$key]['relation'] === 'one-to-many') {
                    $objectMethod = 'set' . ucfirst ($key);
                    if (method_exists($entityClass, $objectMethod)){
                        $entity->$objectMethod(new Collection($config['relations'][$key]['targetEntity'],
                            $config['relations'][$key]['mappedBy'], $this, $entity));
                    }
                }
                if ($config['relations'][$key]['relation'] === 'many-to-one') {
                    $objectMethod = 'set' . ucfirst ($key);
                    if (method_exists($entityClass, $objectMethod)){
                        $joinColumn = $config['relations'][$key]['join_column_name'];
                        $id = $row[$joinColumn];
                        $entity->$objectMethod(new ModelProxy($config['relations'][$key]['targetEntity'], $this, $entity, $id));
                    }
                }
            }
            $resultArray[] = $entity;
        }
        return $resultArray;
    }

    public function fetchObject($entityClass, $container, $id){

        $joinColumnName = null;

        $containerId = $container->getId();
        $containerReflection = new \ReflectionClass($container);
        $containerName = '\\' . $containerReflection->getName();

        $config = $this->schema[$containerName];
        foreach ($config['relations'] as $key => $relation){
            if ($relation['targetEntity'] == $entityClass){
                $joinColumnName = $relation['join_column_name'];
            }
        }

        $config = $this->schema[$entityClass];

        $function = new \ReflectionClass($entityClass);

        if ($config['table']){
            $tableName = $config['table'];
        } else {
            $tableName = $function->getShortName() . "s";
        }

        $sth = $this->pdo->query("SELECT * FROM `users` WHERE `id` = $id");
        $sth->setFetchMode(\PDO::FETCH_ASSOC);

        $row = $sth->fetch();

        $entity = new $entityClass();

        foreach($config['columns'] as $key => $value){
            $objectMethod = 'set' . ucfirst ($key);
            if (method_exists($entityClass, $objectMethod)){
                $entity->$objectMethod($row[$value]);
            }
        }

        foreach($config['relations'] as $key => $value){
            if ($config['relations'][$key]['relation'] === 'one-to-many') {
                $objectMethod = 'set' . ucfirst ($key);
                if (method_exists($entityClass, $objectMethod)){
                    $entity->$objectMethod(new Collection($config['relations'][$key]['targetEntity'],
                    $config['relations'][$key]['mappedBy'], $this, $entity));
                }
            }
            if ($config['relations'][$key]['relation'] === 'many-to-one') {
                $objectMethod = 'set' . ucfirst ($key);
                if (method_exists($entityClass, $objectMethod)){
                    $joinColumn = $config['relations'][$key]['join_column_name'];
                    $id = $row[$joinColumn];
                    $entity->$objectMethod(new ModelProxy($config['relations'][$key]['targetEntity'], $this, $entity, $id));
                }
            }
        }

        return $entity;
    }

    public function fetchCollection($entityClass, $container){

        $joinColumnName = null;

        $containerId = $container->getId();
        $containerReflection = new \ReflectionClass($container);
        $containerName = '\\' . $containerReflection->getName();

        $config = $this->schema[$entityClass];
        foreach ($config['relations'] as $key => $relation){
            if ($relation['targetEntity'] == $containerName){
                $joinColumnName = $relation['join_column_name'];
            }
        }

        $resultArray = array();

        $function = new \ReflectionClass($entityClass);

        if ($config['table']){
            $tableName = $config['table'];
        } else {
            $tableName = $function->getShortName() . "s";
        }

        $sth = $this->pdo->query("SELECT * FROM `$tableName` WHERE $joinColumnName = $containerId");
        $sth->setFetchMode(\PDO::FETCH_ASSOC);

        while($row = $sth->fetch()){
            $entity = new $entityClass();
            foreach($config['columns'] as $key => $value){
                $objectMethod = 'set' . ucfirst ($key);
                if (method_exists($entityClass, $objectMethod)){
                    $entity->$objectMethod($row[$value]);
                }
            }
            foreach($config['relations'] as $key => $value){
                if ($config['relations'][$key]['relation'] === 'one-to-many') {
                    $objectMethod = 'set' . ucfirst ($key);
                    if (method_exists($entityClass, $objectMethod)){
                        $entity->$objectMethod(new Collection($config['relations'][$key]['targetEntity'],
                            $config['relations'][$key]['mappedBy'], $this, $entity));
                    }
                }
                if ($config['relations'][$key]['relation'] === 'many-to-one') {
                    $objectMethod = 'set' . ucfirst ($key);
                    if (method_exists($entityClass, $objectMethod)){
                        $joinColumn = $config['relations'][$key]['join_column_name'];
                        $id = $row[$joinColumn];
                        $entity->$objectMethod(new ModelProxy($config['relations'][$key]['targetEntity'], $this, $entity, $id));
                    }
                }
            }
            $resultArray[] = $entity;
        }
        return $resultArray;
    }

    public function findOne($entityClass, $params){
        $config = $this->schema[$entityClass];

        $resultArray = array();

        $function = new \ReflectionClass($entityClass);

        if ($config['table']){
            $tableName = $config['table'];
        } else {
            $tableName = $function->getShortName() . "s";
        }

        $sth = $this->pdo->query("SELECT * FROM `$tableName` LIMIT 1");
        $sth->setFetchMode(\PDO::FETCH_ASSOC);

        $row = $sth->fetch();
        $entity = new $entityClass();

        foreach($config['columns'] as $key => $value){
            $objectMethod = 'set' . ucfirst ($key);
            if (method_exists($entityClass, $objectMethod)){
                $entity->$objectMethod($row[$value]);
            }
        }

        foreach($config['relations'] as $key => $value){
            if ($config['relations'][$key]['relation'] === 'one-to-many') {
                $objectMethod = 'set' . ucfirst ($key);
                if (method_exists($entityClass, $objectMethod)){
                    $entity->$objectMethod(new Collection($config['relations'][$key]['targetEntity'],
                        $config['relations'][$key]['mappedBy'], $this, $entity));
                }
            }
            if ($config['relations'][$key]['relation'] === 'many-to-one') {
                $objectMethod = 'set' . ucfirst ($key);
                if (method_exists($entityClass, $objectMethod)){
                    $joinColumn = $config['relations'][$key]['join_column_name'];
                    $id = $row[$joinColumn];
                    $entity->$objectMethod(new ModelProxy($config['relations'][$key]['targetEntity'], $this, $entity, $id));
                }
            }
        }

        return $entity;
    }

    public function save($entity){
        $containerReflection = new \ReflectionClass($entity);
        $containerName = '\\' . $containerReflection->getName();

        $config = $this->schema[$containerName];
        $columns = $config['columns'];

        $params = array();

        array_walk( $columns,
            function( $value, $column ) use ( &$params, $entity ) {
                $getter = "get" . ucfirst($column);
                $params[':'.$column] = $entity->$getter();
            } );

        $sql = 'INSERT INTO `posts` (`'
            . implode( '`, `', array_keys($columns) ).'`) VALUES (:'
            . implode( ',:', array_keys($columns) ).')';

        var_dump($sql);

        $stmt = $this->pdo->prepare( $sql );

        if( count( $params ) > 0 ){
            foreach( $params as $column => $value ){
                $stmt->bindValue( $column, $value );
                var_dump($value);
            }
        }

        $stmt->execute();

        /**
        $sql = 'INSERT INTO '.$this->tableName().' (`'
            . implode( '`, `', array_keys( $this->_data ) ).'`) VALUES (:'
            . implode( ',:', array_keys( $this->_data ) ).')';
        array_walk( $this->_data,
            function( $value, $column ) use ( $base, &$params ) {
                $params[':'.$column] = $value;
            } );
        $this->_query( $sql, $params );
        $this->_id = self::$_dbh->lastInsertId();
         */

        return true;
    }

    public function update($entity){

    }

    public function delete($entity){

    }

} 