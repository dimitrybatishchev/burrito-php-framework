<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 11/29/13
 * Time: 7:20 AM
 */

namespace Burrito\Framework\Dj;

abstract class Model implements \ArrayAccess{

    /**
     * @var string
     */
    protected static $dbtable;

    /**
     * @var array
     */
    protected static $fields = array();

    /**
     * @var EventDispatcher
     */
    protected static $eventd;

    /**
     * @var bool
     */
    protected $isNewRecord = true;

    /**
     * @var bool
     */
    protected $inited = false;

    /**
     * @var bool
     */
    protected $hydrated = false;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $cleanData = array();

    /**
     * @var array
     */
    protected $relationDataCache = array();

    /**
     * @var array
     */
    protected $validationErrors = array();

    /** @var Metadata */
    protected $metadata;

    /**
     * @param array $data
     * @param bool $isNewRecord
     * @param bool $fastRawSet tweak for fast data setup
     * @return \Burrito\Framework\Dj\Model
     */
    public function __construct(array $data = array(), $isNewRecord = true, $fastRawSet = false)
    {
        $this->isNewRecord = $isNewRecord;
        $this->metadata = static::metadata();
        if ($isNewRecord) {
            $this->setFromArray($data);
        } else {
            $this->hydrate($data, $fastRawSet);
        }
        $this->init();
        $this->inited = true;
    }

    /**
     * current model metadata
     * @return Metadata
     */
    public static function metadata(){
        return Metadata::getInstance(get_called_class());
    }

    /**
     * new queryset
     * an iterator represents db rows
     * @param bool $biiig
     * @return Query
     */
    public static function objects($biiig = false)
    {
        return new RowsetQuery(static::metadata());
    }

}
