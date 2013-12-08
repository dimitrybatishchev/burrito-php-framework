<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:32 AM
 */

namespace ModelRepository;

class UserMapper extends AbstractDataMapper
{
    protected $entityTable = "users";

    protected function loadEntity(array $row) {
        return new User(array(
            "id"    => $row["id"],
            "name"  => $row["name"],
            "email" => $row["email"],
            "role"  => $row["role"]));
    }
}