<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;

class sylius_product_archetype_attribute implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_archetype_attribute";

    const COLUMN_ARCHETYPE_ID = "archetype_id";

    const COLUMN_ATTRIBUTE_ID = "attribute_id";

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
    protected $archetype_id;

    /**
     * @var int
     */
    protected $attribute_id;

    /**
     * @var sylius_product_archetype
     */
    protected $97763342732C6CC7;

    /**
     * @var sylius_product_attribute
     */
    protected $97763342B6E62EFA;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_archetype_attribute_U(" : "CALL sylius_product_archetype_attribute_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getArchetype_id(true, $connectionName);
        $this->getAttribute_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->archetype_id) . ',' . Escaper::quoteInt($this->attribute_id) . ');';
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
        if ($cascade && $this->97763342732C6CC7 !== null) {
            $this->97763342732C6CC7->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->97763342B6E62EFA !== null) {
            $this->97763342B6E62EFA->save($cascade, $cycleDetector, $connectionName);
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
        $this->archetype_id = $resultSet->getIntegerValue("archetype_id");
        $this->attribute_id = $resultSet->getIntegerValue("attribute_id");
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
        $archetype_id = Escaper::quoteInt($this->archetype_id);
        $attribute_id = Escaper::quoteInt($this->attribute_id);
        $connection->execute("CALL sylius_product_archetype_attribute_DB_PK($archetype_id,$attribute_id)");
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
        $this->setArchetype_id($arrayAccessor->getIntegerValue("archetype_id"));
        $this->setAttribute_id($arrayAccessor->getIntegerValue("attribute_id"));
        $this->_existing = ($this->archetype_id !== null) && ($this->attribute_id !== null);
        $97763342732C6CC7Array = $arrayAccessor->getArray("97763342732C6CC7");
        if ($97763342732C6CC7Array !== null) {
            $97763342732C6CC7 = sylius_product_archetypeService::getInstance()->newInstance();
            $97763342732C6CC7->fromArray($97763342732C6CC7Array);
            $this->set97763342732C6CC7($97763342732C6CC7);
        }
        $97763342B6E62EFAArray = $arrayAccessor->getArray("97763342B6E62EFA");
        if ($97763342B6E62EFAArray !== null) {
            $97763342B6E62EFA = sylius_product_attributeService::getInstance()->newInstance();
            $97763342B6E62EFA->fromArray($97763342B6E62EFAArray);
            $this->set97763342B6E62EFA($97763342B6E62EFA);
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
            "archetype_id" => $this->getArchetype_id(),
            "attribute_id" => $this->getAttribute_id()
        ];
        if ($this->97763342732C6CC7 !== null) {
            $result["97763342732C6CC7"] = $this->97763342732C6CC7->toArray($cycleDetector);
        }
        if ($this->97763342B6E62EFA !== null) {
            $result["97763342B6E62EFA"] = $this->97763342B6E62EFA->toArray($cycleDetector);
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
    public function getArchetype_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->archetype_id === null) {
            $this->archetype_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->archetype_id;
    }

    /**
     * @param int $archetype_id
     * 
     * @return void
     */
    public function setArchetype_id(int $archetype_id = null)
    {
        $this->archetype_id = $archetype_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getAttribute_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->attribute_id === null) {
            $this->attribute_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->attribute_id;
    }

    /**
     * @param int $attribute_id
     * 
     * @return void
     */
    public function setAttribute_id(int $attribute_id = null)
    {
        $this->attribute_id = $attribute_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_archetype|null
     */
    public function get97763342732C6CC7(bool $forceReload = false)
    {
        if ($this->97763342732C6CC7 === null || $forceReload) {
            $this->97763342732C6CC7 = sylius_product_archetypeService::getInstance()->getEntityByPrimaryKey($this->archetype_id);
        }
        return $this->97763342732C6CC7;
    }

    /**
     * @param sylius_product_archetype $entity
     * 
     * @return void
     */
    public function set97763342732C6CC7(sylius_product_archetype $entity = null)
    {
        $this->97763342732C6CC7 = $entity;
        $this->archetype_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_attribute|null
     */
    public function get97763342B6E62EFA(bool $forceReload = false)
    {
        if ($this->97763342B6E62EFA === null || $forceReload) {
            $this->97763342B6E62EFA = sylius_product_attributeService::getInstance()->getEntityByPrimaryKey($this->attribute_id);
        }
        return $this->97763342B6E62EFA;
    }

    /**
     * @param sylius_product_attribute $entity
     * 
     * @return void
     */
    public function set97763342B6E62EFA(sylius_product_attribute $entity = null)
    {
        $this->97763342B6E62EFA = $entity;
        $this->attribute_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_archetype_attribute $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_archetype_attribute $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getArchetype_id() === $entity->getArchetype_id() && $this->getAttribute_id() === $entity->getAttribute_id();
    }

}