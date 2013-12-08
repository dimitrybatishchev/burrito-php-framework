<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 12/2/13
 * Time: 6:50 AM
 */

namespace Burrito\Framework\Model;


class Collection implements \IteratorAggregate
{
    protected $entityManager;
    protected $targetEntity;
    protected $mappedBy;

    protected $_iterator;

    public function __construct($targetEntity, $mappedBy, $entityManager, array $data = array()){
        $this->targetEntity = $targetEntity;
        $this->mappedBy = $mappedBy;
        $this->entityManager = $entityManager;

        if (!empty($data)){
            $this->data = $data;
        }
    }

    /**
     * Get the external iterator
     */
    public function getIterator()
    {
        if ($this->_iterator === null) {
            if (($data = file_get_contents($this->_file)) !== false) {
                $data = explode("\n", $data);
                $this->_iterator = new ArrayCollection($data);
            }
        }
        return $this->_iterator;
    }
}