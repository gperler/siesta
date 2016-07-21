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

class SyliusSearchIndex implements ArraySerializable
{

    const TABLE_NAME = "sylius_search_index";

    const COLUMN_ID = "id";

    const COLUMN_ITEMID = "item_id";

    const COLUMN_ENTITY = "entity";

    const COLUMN_VALUE = "value";

    const COLUMN_TAGS = "tags";

    const COLUMN_CREATEDAT = "created_at";

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
    protected $itemId;

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
    protected $createdAt;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->itemId) . ',' . Escaper::quoteString($connection, $this->entity) . ',' . Escaper::quoteString($connection, $this->value) . ',' . Escaper::quoteString($connection, $this->tags) . ',' . Escaper::quoteDateTime($this->createdAt) . ');';
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
        $this->itemId = $resultSet->getIntegerValue("item_id");
        $this->entity = $resultSet->getStringValue("entity");
        $this->value = $resultSet->getStringValue("value");
        $this->tags = $resultSet->getStringValue("tags");
        $this->createdAt = $resultSet->getDateTime("created_at");
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
        $this->setItemId($arrayAccessor->getIntegerValue("itemId"));
        $this->setEntity($arrayAccessor->getStringValue("entity"));
        $this->setValue($arrayAccessor->getStringValue("value"));
        $this->setTags($arrayAccessor->getStringValue("tags"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
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
            "itemId" => $this->getItemId(),
            "entity" => $this->getEntity(),
            "value" => $this->getValue(),
            "tags" => $this->getTags(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null
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
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param int $itemId
     * 
     * @return void
     */
    public function setItemId(int $itemId = null)
    {
        $this->itemId = $itemId;
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param SiestaDateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(SiestaDateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param SyliusSearchIndex $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusSearchIndex $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}