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

class SyliusProductOptions implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_options";

    const COLUMN_PRODUCTID = "product_id";

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
    protected $productId;

    /**
     * @var int
     */
    protected $optionId;

    /**
     * @var SyliusProduct
     */
    protected $2B5FF0094584665A;

    /**
     * @var SyliusProductOption
     */
    protected $2B5FF009A7C41D6F;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_options_U(" : "CALL sylius_product_options_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getProductId(true, $connectionName);
        $this->getOptionId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->productId) . ',' . Escaper::quoteInt($this->optionId) . ');';
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
        if ($cascade && $this->2B5FF0094584665A !== null) {
            $this->2B5FF0094584665A->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->2B5FF009A7C41D6F !== null) {
            $this->2B5FF009A7C41D6F->save($cascade, $cycleDetector, $connectionName);
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
        $this->productId = $resultSet->getIntegerValue("product_id");
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
        $productId = Escaper::quoteInt($this->productId);
        $optionId = Escaper::quoteInt($this->optionId);
        $connection->execute("CALL sylius_product_options_DB_PK($productId,$optionId)");
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
        $this->setProductId($arrayAccessor->getIntegerValue("productId"));
        $this->setOptionId($arrayAccessor->getIntegerValue("optionId"));
        $this->_existing = ($this->productId !== null) && ($this->optionId !== null);
        $2B5FF0094584665AArray = $arrayAccessor->getArray("2B5FF0094584665A");
        if ($2B5FF0094584665AArray !== null) {
            $2B5FF0094584665A = SyliusProductService::getInstance()->newInstance();
            $2B5FF0094584665A->fromArray($2B5FF0094584665AArray);
            $this->set2B5FF0094584665A($2B5FF0094584665A);
        }
        $2B5FF009A7C41D6FArray = $arrayAccessor->getArray("2B5FF009A7C41D6F");
        if ($2B5FF009A7C41D6FArray !== null) {
            $2B5FF009A7C41D6F = SyliusProductOptionService::getInstance()->newInstance();
            $2B5FF009A7C41D6F->fromArray($2B5FF009A7C41D6FArray);
            $this->set2B5FF009A7C41D6F($2B5FF009A7C41D6F);
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
            "productId" => $this->getProductId(),
            "optionId" => $this->getOptionId()
        ];
        if ($this->2B5FF0094584665A !== null) {
            $result["2B5FF0094584665A"] = $this->2B5FF0094584665A->toArray($cycleDetector);
        }
        if ($this->2B5FF009A7C41D6F !== null) {
            $result["2B5FF009A7C41D6F"] = $this->2B5FF009A7C41D6F->toArray($cycleDetector);
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
    public function getProductId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->productId === null) {
            $this->productId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @return SyliusProduct|null
     */
    public function get2B5FF0094584665A(bool $forceReload = false)
    {
        if ($this->2B5FF0094584665A === null || $forceReload) {
            $this->2B5FF0094584665A = SyliusProductService::getInstance()->getEntityByPrimaryKey($this->productId);
        }
        return $this->2B5FF0094584665A;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return void
     */
    public function set2B5FF0094584665A(SyliusProduct $entity = null)
    {
        $this->2B5FF0094584665A = $entity;
        $this->productId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductOption|null
     */
    public function get2B5FF009A7C41D6F(bool $forceReload = false)
    {
        if ($this->2B5FF009A7C41D6F === null || $forceReload) {
            $this->2B5FF009A7C41D6F = SyliusProductOptionService::getInstance()->getEntityByPrimaryKey($this->optionId);
        }
        return $this->2B5FF009A7C41D6F;
    }

    /**
     * @param SyliusProductOption $entity
     * 
     * @return void
     */
    public function set2B5FF009A7C41D6F(SyliusProductOption $entity = null)
    {
        $this->2B5FF009A7C41D6F = $entity;
        $this->optionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductOptions $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductOptions $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProductId() === $entity->getProductId() && $this->getOptionId() === $entity->getOptionId();
    }

}