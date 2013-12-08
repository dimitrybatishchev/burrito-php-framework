<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 8:26 PM
 */

namespace Burrito\Framework\UOW;


class UnitOfWorkFactory
{
    /**
     * Create a Unit of Work with the specified name
     */
    public function create($name)
    {
        $name = ucfirst(strtolower($name));
        $collection = $name . 'Collection';
        $dataMapper = $name . 'Mapper';
        $unitOfWork = $name . 'UnitOfWork';
        return new $unitOfWork(
            new $dataMapper(
                MySQLAdapter::getInstance(), new $collection
            )
        );
    }
}