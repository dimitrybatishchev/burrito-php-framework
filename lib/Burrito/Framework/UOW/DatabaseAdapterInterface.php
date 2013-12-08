<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/28/13
 * Time: 6:05 AM
 */

namespace Burrito\Framework\UOW;

interface DatabaseAdapterInterface
{
    function connect();

    function disconnect();

    function query($query);

    function fetch();

    function select($table, $where, $fields, $order, $limit, $offset);

    function insert($table, array $data);

    function update($table, array $data, $where);

    function delete($table, $where);

    function getInsertId();

    function countRows();

    function getAffectedRows();
}