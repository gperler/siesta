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

class SyliusProductTaxon implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_taxon";

    const COLUMN_PRODUCTID = "product_id";

    const COLUMN_TAXONID = "taxon_id";

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
    protected $taxonId;

    /**
     * @var SyliusProduct
     */
    protected $169C6CD94584665A;

    /**
     * @var SyliusTaxon
     */
    protected $169C6CD9DE13F470;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_taxon_U(" : "CALL sylius_product_taxon_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getProductId(true, $connectionName);
        $this->getTaxonId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->productId) . ',' . Escaper::quoteInt($this->taxonId) . ');';
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
        if ($cascade && $this->169C6CD94584665A !== null) {
            $this->169C6CD94584665A->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->169C6CD9DE13F470 !== null) {
            $this->169C6CD9DE13F470->save($cascade, $cycleDetector, $connectionName);
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
        $this->taxonId = $resultSet->getIntegerValue("taxon_id");
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
        $taxonId = Escaper::quoteInt($this->taxonId);
        $connection->execute("CALL sylius_product_taxon_DB_PK($productId,$taxonId)");
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
        $this->setTaxonId($arrayAccessor->getIntegerValue("taxonId"));
        $this->_existing = ($this->productId !== null) && ($this->taxonId !== null);
        $169C6CD94584665AArray = $arrayAccessor->getArray("169C6CD94584665A");
        if ($169C6CD94584665AArray !== null) {
            $169C6CD94584665A = SyliusProductService::getInstance()->newInstance();
            $169C6CD94584665A->fromArray($169C6CD94584665AArray);
            $this->set169C6CD94584665A($169C6CD94584665A);
        }
        $169C6CD9DE13F470Array = $arrayAccessor->getArray("169C6CD9DE13F470");
        if ($169C6CD9DE13F470Array !== null) {
            $169C6CD9DE13F470 = SyliusTaxonService::getInstance()->newInstance();
            $169C6CD9DE13F470->fromArray($169C6CD9DE13F470Array);
            $this->set169C6CD9DE13F470($169C6CD9DE13F470);
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
            "taxonId" => $this->getTaxonId()
        ];
        if ($this->169C6CD94584665A !== null) {
            $result["169C6CD94584665A"] = $this->169C6CD94584665A->toArray($cycleDetector);
        }
        if ($this->169C6CD9DE13F470 !== null) {
            $result["169C6CD9DE13F470"] = $this->169C6CD9DE13F470->toArray($cycleDetector);
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
    public function getTaxonId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->taxonId === null) {
            $this->taxonId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->taxonId;
    }

    /**
     * @param int $taxonId
     * 
     * @return void
     */
    public function setTaxonId(int $taxonId = null)
    {
        $this->taxonId = $taxonId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProduct|null
     */
    public function get169C6CD94584665A(bool $forceReload = false)
    {
        if ($this->169C6CD94584665A === null || $forceReload) {
            $this->169C6CD94584665A = SyliusProductService::getInstance()->getEntityByPrimaryKey($this->productId);
        }
        return $this->169C6CD94584665A;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return void
     */
    public function set169C6CD94584665A(SyliusProduct $entity = null)
    {
        $this->169C6CD94584665A = $entity;
        $this->productId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusTaxon|null
     */
    public function get169C6CD9DE13F470(bool $forceReload = false)
    {
        if ($this->169C6CD9DE13F470 === null || $forceReload) {
            $this->169C6CD9DE13F470 = SyliusTaxonService::getInstance()->getEntityByPrimaryKey($this->taxonId);
        }
        return $this->169C6CD9DE13F470;
    }

    /**
     * @param SyliusTaxon $entity
     * 
     * @return void
     */
    public function set169C6CD9DE13F470(SyliusTaxon $entity = null)
    {
        $this->169C6CD9DE13F470 = $entity;
        $this->taxonId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductTaxon $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductTaxon $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProductId() === $entity->getProductId() && $this->getTaxonId() === $entity->getTaxonId();
    }

}