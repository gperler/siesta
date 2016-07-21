<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class sylius_search_index implements ArraySerializable
{

    const TABLE_NAME = "sylius_search_index";

    const COLUMN_ID = "id";

    const COLUMN_ITEM_ID = "item_id";

    const COLUMN_ENTITY = "entity";

    const COLUMN_VALUE = "value";

    const COLUMN_TAGS = "tags";

    const COLUMN_CREATED_AT = "created_at";

    /**
     * @var bool
     */
    protected $_existing;

    /**
     * @var array
     */
    protected $_rawJSON;

    /**
     * @var array
     */
    protected $_rawSQLResult;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $item_id;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $tags;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL sylius_search_index_U(" : "CALL sylius_search_index_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->item_id) . ',' . Escaper::quoteString($connection, $this->entity) . ',' . Escaper::quoteString($connection, $this->value) . ',' . Escaper::quoteString($connection, $this->tags) . ',' . Escaper::quoteDateTime($this->created_at) . ');';
    }

    /**
     * @param bool $cascade
     * @param CycleDetector $cycleDetector
     * @param string $connectionName
     * 
     * @return void
     */
    public function save(bool $cascade = false, CycleDetector $cycleDetector = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return;
        }
        $call = $this->createSaveStoredProcedureCall($connectionName);
        $connection->execute($call);
        $this->_existing = true;
        if (!$cascade) {
            return;
        }
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return void
     */
    public function fromResultSet(ResultSet $resultSet)
    {
        $this->_existing = true;
        $this->_rawSQLResult = $resultSet->getNext();
        $this->id = $resultSet->getIntegerValue("id");
        $this->item_id = $resultSet->getIntegerValue("item_id");
        $this->entity = $resultSet->getStringValue("entity");
        $this->value = $resultSet->getStringValue("value");
        $this->tags = $resultSet->getStringValue("tags");
        $this->created_at = $resultSet->getDateTime("created_at");
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function getAdditionalColumn(string $key)
    {
        return ArrayUtil::getFromArray($this->_rawSQLResult, $key);
    }

    /**
     * @param string $connectionName
     * 
     * @return void
     */
    public function delete(string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($this->id);
        $connection->execute("CALL sylius_search_index_DB_PK($id)");
        $this->_existing = false;
    }

    /**
     * @param array $data
     * 
     * @return void
     */
    public function fromArray(array $data)
    {
        $this->_rawJSON = $data;
        $arrayAccessor = new ArrayAccessor($data);
        $this->setId($arrayAccessor->getIntegerValue("id"));
        $this->setItem_id($arrayAccessor->getIntegerValue("item_id"));
        $this->setEntity($arrayAccessor->getStringValue("entity"));
        $this->setValue($arrayAccessor->getStringValue("value"));
        $this->setTags($arrayAccessor->getStringValue("tags"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->_existing = ($this->id !== null);
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return array|null
     */
    public function toArray(CycleDetector $cycleDetector = null)
    {
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return null;
        }
        $result = [
            "id" => $this->getId(),
            "item_id" => $this->getItem_id(),
            "entity" => $this->getEntity(),
            "value" => $this->getValue(),
            "tags" => $this->getTags(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null
        ];
        return $result;
    }

    /**
     * @param string $jsonString
     * 
     * @return void
     */
    public function fromJSON(string $jsonString)
    {
        $this->fromArray(json_decode($jsonString, true));
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return string
     */
    public function toJSON(CycleDetector $cycleDetector = null) : string
    {
        return json_encode($this->toArray($cycleDetector));
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return int|null
     */
    public function getItem_id()
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     * 
     * @return void
     */
    public function setItem_id(int $item_id = null)
    {
        $this->item_id = $item_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     * 
     * @return void
     */
    public function setEntity(string $entity = null)
    {
        $this->entity = StringUtil::trimToNull($entity, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * 
     * @return void
     */
    public function setValue(string $value = null)
    {
        $this->value = StringUtil::trimToNull($value, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     * 
     * @return void
     */
    public function setTags(string $tags = null)
    {
        $this->tags = StringUtil::trimToNull($tags, null);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * @param SiestaDateTime $created_at
     * 
     * @return void
     */
    public function setCreated_at(SiestaDateTime $created_at = null)
    {
        $this->created_at = $created_at;
    }

    /**
     * @param sylius_search_index $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_search_index $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}