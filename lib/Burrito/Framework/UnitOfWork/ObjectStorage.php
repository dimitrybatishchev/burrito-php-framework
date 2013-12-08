<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/27/13
 * Time: 10:30 AM
 */

namespace ModelRepository;


class ObjectStorage extends \SplObjectStorage implements \ObjectStorageInterface
{
    public function clear() {
        $tempStorage = clone $this;
        $this->addAll($tempStorage);
        $this->removeAll($tempStorage);
        $tempStorage = null;
    }
}