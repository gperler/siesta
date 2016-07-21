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

class SyliusProductArchetypeAttribute implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_archetype_attribute";

    const COLUMN_ARCHETYPEID = "archetype_id";

    const COLUMN_ATTRIBUTEID = "attribute_id";

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
    protected $archetypeId;

    /**
     * @var int
     */
    protected $attributeId;

    /**
     * @var SyliusProductArchetype
     */
    protected $97763342732C6CC7;

    /**
     * @var SyliusProductAttribute
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
        $this->getArchetypeId(true, $connectionName);
        $this->getAttributeId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->archetypeId) . ',' . Escaper::quoteInt($this->attributeId) . ');';
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
        $this->archetypeId = $resultSet->getIntegerValue("archetype_id");
        $this->attributeId = $resultSet->getIntegerValue("attribute_id");
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
        $archetypeId = Escaper::quoteInt($this->archetypeId);
        $attributeId = Escaper::quoteInt($this->attributeId);
        $connection->execute("CALL sylius_product_archetype_attribute_DB_PK($archetypeId,$attributeId)");
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
        $this->setArchetypeId($arrayAccessor->getIntegerValue("archetypeId"));
        $this->setAttributeId($arrayAccessor->getIntegerValue("attributeId"));
        $this->_existing = ($this->archetypeId !== null) && ($this->attributeId !== null);
        $97763342732C6CC7Array = $arrayAccessor->getArray("97763342732C6CC7");
        if ($97763342732C6CC7Array !== null) {
            $97763342732C6CC7 = SyliusProductArchetypeService::getInstance()->newInstance();
            $97763342732C6CC7->fromArray($97763342732C6CC7Array);
            $this->set97763342732C6CC7($97763342732C6CC7);
        }
        $97763342B6E62EFAArray = $arrayAccessor->getArray("97763342B6E62EFA");
        if ($97763342B6E62EFAArray !== null) {
            $97763342B6E62EFA = SyliusProductAttributeService::getInstance()->newInstance();
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
            "archetypeId" => $this->getArchetypeId(),
            "attributeId" => $this->getAttributeId()
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
    public function getArchetypeId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->archetypeId === null) {
            $this->archetypeId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->archetypeId;
    }

    /**
     * @param int $archetypeId
     * 
     * @return void
     */
    public function setArchetypeId(int $archetypeId = null)
    {
        $this->archetypeId = $archetypeId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getAttributeId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->attributeId === null) {
            $this->attributeId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->attributeId;
    }

    /**
     * @param int $attributeId
     * 
     * @return void
     */
    public function setAttributeId(int $attributeId = null)
    {
        $this->attributeId = $attributeId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductArchetype|null
     */
    public function get97763342732C6CC7(bool $forceReload = false)
    {
        if ($this->97763342732C6CC7 === null || $forceReload) {
            $this->97763342732C6CC7 = SyliusProductArchetypeService::getInstance()->getEntityByPrimaryKey($this->archetypeId);
        }
        return $this->97763342732C6CC7;
    }

    /**
     * @param SyliusProductArchetype $entity
     * 
     * @return void
     */
    public function set97763342732C6CC7(SyliusProductArchetype $entity = null)
    {
        $this->97763342732C6CC7 = $entity;
        $this->archetypeId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductAttribute|null
     */
    public function get97763342B6E62EFA(bool $forceReload = false)
    {
        if ($this->97763342B6E62EFA === null || $forceReload) {
            $this->97763342B6E62EFA = SyliusProductAttributeService::getInstance()->getEntityByPrimaryKey($this->attributeId);
        }
        return $this->97763342B6E62EFA;
    }

    /**
     * @param SyliusProductAttribute $entity
     * 
     * @return void
     */
    public function set97763342B6E62EFA(SyliusProductAttribute $entity = null)
    {
        $this->97763342B6E62EFA = $entity;
        $this->attributeId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductArchetypeAttribute $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductArchetypeAttribute $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getArchetypeId() === $entity->getArchetypeId() && $this->getAttributeId() === $entity->getAttributeId();
    }

}