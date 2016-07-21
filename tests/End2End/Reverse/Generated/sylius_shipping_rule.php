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
use Siesta\Util\StringUtil;

class sylius_shipping_rule implements ArraySerializable
{

    const TABLE_NAME = "sylius_shipping_rule";

    const COLUMN_ID = "id";

    const COLUMN_METHOD_ID = "method_id";

    const COLUMN_TYPE = "type";

    const COLUMN_CONFIGURATION = "configuration";

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
    protected $method_id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $configuration;

    /**
     * @var sylius_shipping_method
     */
    protected $3BC30EE019883967;

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
        $spCall = ($this->_existing) ? "CALL sylius_shipping_rule_U(" : "CALL sylius_shipping_rule_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->method_id) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->configuration) . ');';
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
        if ($cascade && $this->3BC30EE019883967 !== null) {
            $this->3BC30EE019883967->save($cascade, $cycleDetector, $connectionName);
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
        $this->method_id = $resultSet->getIntegerValue("method_id");
        $this->type = $resultSet->getStringValue("type");
        $this->configuration = $resultSet->getStringValue("configuration");
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
        $connection->execute("CALL sylius_shipping_rule_DB_PK($id)");
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
        $this->setMethod_id($arrayAccessor->getIntegerValue("method_id"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setConfiguration($arrayAccessor->getStringValue("configuration"));
        $this->_existing = ($this->id !== null);
        $3BC30EE019883967Array = $arrayAccessor->getArray("3BC30EE019883967");
        if ($3BC30EE019883967Array !== null) {
            $3BC30EE019883967 = sylius_shipping_methodService::getInstance()->newInstance();
            $3BC30EE019883967->fromArray($3BC30EE019883967Array);
            $this->set3BC30EE019883967($3BC30EE019883967);
        }
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
            "method_id" => $this->getMethod_id(),
            "type" => $this->getType(),
            "configuration" => $this->getConfiguration()
        ];
        if ($this->3BC30EE019883967 !== null) {
            $result["3BC30EE019883967"] = $this->3BC30EE019883967->toArray($cycleDetector);
        }
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
    public function getMethod_id()
    {
        return $this->method_id;
    }

    /**
     * @param int $method_id
     * 
     * @return void
     */
    public function setMethod_id(int $method_id = null)
    {
        $this->method_id = $method_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type = null)
    {
        $this->type = StringUtil::trimToNull($type, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param string $configuration
     * 
     * @return void
     */
    public function setConfiguration(string $configuration = null)
    {
        $this->configuration = StringUtil::trimToNull($configuration, null);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_shipping_method|null
     */
    public function get3BC30EE019883967(bool $forceReload = false)
    {
        if ($this->3BC30EE019883967 === null || $forceReload) {
            $this->3BC30EE019883967 = sylius_shipping_methodService::getInstance()->getEntityByPrimaryKey($this->method_id);
        }
        return $this->3BC30EE019883967;
    }

    /**
     * @param sylius_shipping_method $entity
     * 
     * @return void
     */
    public function set3BC30EE019883967(sylius_shipping_method $entity = null)
    {
        $this->3BC30EE019883967 = $entity;
        $this->method_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_shipping_rule $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_shipping_rule $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}