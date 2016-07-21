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

class SyliusProductAttributeValue implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_attribute_value";

    const COLUMN_ID = "id";

    const COLUMN_PRODUCTID = "product_id";

    const COLUMN_ATTRIBUTEID = "attribute_id";

    const COLUMN_VALUE = "value";

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
    protected $productId;

    /**
     * @var int
     */
    protected $attributeId;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var SyliusProduct
     */
    protected $8A053E544584665A;

    /**
     * @var SyliusProductAttribute
     */
    protected $8A053E54B6E62EFA;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_attribute_value_U(" : "CALL sylius_product_attribute_value_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->productId) . ',' . Escaper::quoteInt($this->attributeId) . ',' . Escaper::quoteString($connection, $this->value) . ');';
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
        if ($cascade && $this->8A053E544584665A !== null) {
            $this->8A053E544584665A->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->8A053E54B6E62EFA !== null) {
            $this->8A053E54B6E62EFA->save($cascade, $cycleDetector, $connectionName);
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
        $this->productId = $resultSet->getIntegerValue("product_id");
        $this->attributeId = $resultSet->getIntegerValue("attribute_id");
        $this->value = $resultSet->getStringValue("value");
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
        $connection->execute("CALL sylius_product_attribute_value_DB_PK($id)");
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
        $this->setProductId($arrayAccessor->getIntegerValue("productId"));
        $this->setAttributeId($arrayAccessor->getIntegerValue("attributeId"));
        $this->setValue($arrayAccessor->getStringValue("value"));
        $this->_existing = ($this->id !== null);
        $8A053E544584665AArray = $arrayAccessor->getArray("8A053E544584665A");
        if ($8A053E544584665AArray !== null) {
            $8A053E544584665A = SyliusProductService::getInstance()->newInstance();
            $8A053E544584665A->fromArray($8A053E544584665AArray);
            $this->set8A053E544584665A($8A053E544584665A);
        }
        $8A053E54B6E62EFAArray = $arrayAccessor->getArray("8A053E54B6E62EFA");
        if ($8A053E54B6E62EFAArray !== null) {
            $8A053E54B6E62EFA = SyliusProductAttributeService::getInstance()->newInstance();
            $8A053E54B6E62EFA->fromArray($8A053E54B6E62EFAArray);
            $this->set8A053E54B6E62EFA($8A053E54B6E62EFA);
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
            "productId" => $this->getProductId(),
            "attributeId" => $this->getAttributeId(),
            "value" => $this->getValue()
        ];
        if ($this->8A053E544584665A !== null) {
            $result["8A053E544584665A"] = $this->8A053E544584665A->toArray($cycleDetector);
        }
        if ($this->8A053E54B6E62EFA !== null) {
            $result["8A053E54B6E62EFA"] = $this->8A053E54B6E62EFA->toArray($cycleDetector);
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
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * 
     * @return void
     */
    public function setProductId(int $productId = null)
    {
        $this->productId = $productId;
    }

    /**
     * 
     * @return int|null
     */
    public function getAttributeId()
    {
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
     * @param bool $forceReload
     * 
     * @return SyliusProduct|null
     */
    public function get8A053E544584665A(bool $forceReload = false)
    {
        if ($this->8A053E544584665A === null || $forceReload) {
            $this->8A053E544584665A = SyliusProductService::getInstance()->getEntityByPrimaryKey($this->productId);
        }
        return $this->8A053E544584665A;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return void
     */
    public function set8A053E544584665A(SyliusProduct $entity = null)
    {
        $this->8A053E544584665A = $entity;
        $this->productId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductAttribute|null
     */
    public function get8A053E54B6E62EFA(bool $forceReload = false)
    {
        if ($this->8A053E54B6E62EFA === null || $forceReload) {
            $this->8A053E54B6E62EFA = SyliusProductAttributeService::getInstance()->getEntityByPrimaryKey($this->attributeId);
        }
        return $this->8A053E54B6E62EFA;
    }

    /**
     * @param SyliusProductAttribute $entity
     * 
     * @return void
     */
    public function set8A053E54B6E62EFA(SyliusProductAttribute $entity = null)
    {
        $this->8A053E54B6E62EFA = $entity;
        $this->attributeId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductAttributeValue $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductAttributeValue $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}