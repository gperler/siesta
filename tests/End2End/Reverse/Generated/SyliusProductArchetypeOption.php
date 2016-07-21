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

class SyliusProductArchetypeOption implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_archetype_option";

    const COLUMN_PRODUCTARCHETYPEID = "product_archetype_id";

    const COLUMN_OPTIONID = "option_id";

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
    protected $productArchetypeId;

    /**
     * @var int
     */
    protected $optionId;

    /**
     * @var SyliusProductOption
     */
    protected $BCE763A7A7C41D6F;

    /**
     * @var SyliusProductArchetype
     */
    protected $BCE763A7FE884EAC;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_archetype_option_U(" : "CALL sylius_product_archetype_option_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getProductArchetypeId(true, $connectionName);
        $this->getOptionId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->productArchetypeId) . ',' . Escaper::quoteInt($this->optionId) . ');';
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
        if ($cascade && $this->BCE763A7A7C41D6F !== null) {
            $this->BCE763A7A7C41D6F->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->BCE763A7FE884EAC !== null) {
            $this->BCE763A7FE884EAC->save($cascade, $cycleDetector, $connectionName);
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
        $this->productArchetypeId = $resultSet->getIntegerValue("product_archetype_id");
        $this->optionId = $resultSet->getIntegerValue("option_id");
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
        $productArchetypeId = Escaper::quoteInt($this->productArchetypeId);
        $optionId = Escaper::quoteInt($this->optionId);
        $connection->execute("CALL sylius_product_archetype_option_DB_PK($productArchetypeId,$optionId)");
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
        $this->setProductArchetypeId($arrayAccessor->getIntegerValue("productArchetypeId"));
        $this->setOptionId($arrayAccessor->getIntegerValue("optionId"));
        $this->_existing = ($this->productArchetypeId !== null) && ($this->optionId !== null);
        $BCE763A7A7C41D6FArray = $arrayAccessor->getArray("BCE763A7A7C41D6F");
        if ($BCE763A7A7C41D6FArray !== null) {
            $BCE763A7A7C41D6F = SyliusProductOptionService::getInstance()->newInstance();
            $BCE763A7A7C41D6F->fromArray($BCE763A7A7C41D6FArray);
            $this->setBCE763A7A7C41D6F($BCE763A7A7C41D6F);
        }
        $BCE763A7FE884EACArray = $arrayAccessor->getArray("BCE763A7FE884EAC");
        if ($BCE763A7FE884EACArray !== null) {
            $BCE763A7FE884EAC = SyliusProductArchetypeService::getInstance()->newInstance();
            $BCE763A7FE884EAC->fromArray($BCE763A7FE884EACArray);
            $this->setBCE763A7FE884EAC($BCE763A7FE884EAC);
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
            "productArchetypeId" => $this->getProductArchetypeId(),
            "optionId" => $this->getOptionId()
        ];
        if ($this->BCE763A7A7C41D6F !== null) {
            $result["BCE763A7A7C41D6F"] = $this->BCE763A7A7C41D6F->toArray($cycleDetector);
        }
        if ($this->BCE763A7FE884EAC !== null) {
            $result["BCE763A7FE884EAC"] = $this->BCE763A7FE884EAC->toArray($cycleDetector);
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
    public function getProductArchetypeId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->productArchetypeId === null) {
            $this->productArchetypeId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->productArchetypeId;
    }

    /**
     * @param int $productArchetypeId
     * 
     * @return void
     */
    public function setProductArchetypeId(int $productArchetypeId = null)
    {
        $this->productArchetypeId = $productArchetypeId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getOptionId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->optionId === null) {
            $this->optionId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->optionId;
    }

    /**
     * @param int $optionId
     * 
     * @return void
     */
    public function setOptionId(int $optionId = null)
    {
        $this->optionId = $optionId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductOption|null
     */
    public function getBCE763A7A7C41D6F(bool $forceReload = false)
    {
        if ($this->BCE763A7A7C41D6F === null || $forceReload) {
            $this->BCE763A7A7C41D6F = SyliusProductOptionService::getInstance()->getEntityByPrimaryKey($this->optionId);
        }
        return $this->BCE763A7A7C41D6F;
    }

    /**
     * @param SyliusProductOption $entity
     * 
     * @return void
     */
    public function setBCE763A7A7C41D6F(SyliusProductOption $entity = null)
    {
        $this->BCE763A7A7C41D6F = $entity;
        $this->optionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductArchetype|null
     */
    public function getBCE763A7FE884EAC(bool $forceReload = false)
    {
        if ($this->BCE763A7FE884EAC === null || $forceReload) {
            $this->BCE763A7FE884EAC = SyliusProductArchetypeService::getInstance()->getEntityByPrimaryKey($this->productArchetypeId);
        }
        return $this->BCE763A7FE884EAC;
    }

    /**
     * @param SyliusProductArchetype $entity
     * 
     * @return void
     */
    public function setBCE763A7FE884EAC(SyliusProductArchetype $entity = null)
    {
        $this->BCE763A7FE884EAC = $entity;
        $this->productArchetypeId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductArchetypeOption $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductArchetypeOption $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProductArchetypeId() === $entity->getProductArchetypeId() && $this->getOptionId() === $entity->getOptionId();
    }

}