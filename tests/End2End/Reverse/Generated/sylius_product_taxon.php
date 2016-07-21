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

class sylius_product_taxon implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_taxon";

    const COLUMN_PRODUCT_ID = "product_id";

    const COLUMN_TAXON_ID = "taxon_id";

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
    protected $product_id;

    /**
     * @var int
     */
    protected $taxon_id;

    /**
     * @var sylius_product
     */
    protected $169C6CD94584665A;

    /**
     * @var sylius_taxon
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
        $this->getProduct_id(true, $connectionName);
        $this->getTaxon_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->product_id) . ',' . Escaper::quoteInt($this->taxon_id) . ');';
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
        $this->product_id = $resultSet->getIntegerValue("product_id");
        $this->taxon_id = $resultSet->getIntegerValue("taxon_id");
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
        $product_id = Escaper::quoteInt($this->product_id);
        $taxon_id = Escaper::quoteInt($this->taxon_id);
        $connection->execute("CALL sylius_product_taxon_DB_PK($product_id,$taxon_id)");
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
        $this->setProduct_id($arrayAccessor->getIntegerValue("product_id"));
        $this->setTaxon_id($arrayAccessor->getIntegerValue("taxon_id"));
        $this->_existing = ($this->product_id !== null) && ($this->taxon_id !== null);
        $169C6CD94584665AArray = $arrayAccessor->getArray("169C6CD94584665A");
        if ($169C6CD94584665AArray !== null) {
            $169C6CD94584665A = sylius_productService::getInstance()->newInstance();
            $169C6CD94584665A->fromArray($169C6CD94584665AArray);
            $this->set169C6CD94584665A($169C6CD94584665A);
        }
        $169C6CD9DE13F470Array = $arrayAccessor->getArray("169C6CD9DE13F470");
        if ($169C6CD9DE13F470Array !== null) {
            $169C6CD9DE13F470 = sylius_taxonService::getInstance()->newInstance();
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
            "product_id" => $this->getProduct_id(),
            "taxon_id" => $this->getTaxon_id()
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
    public function getProduct_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->product_id === null) {
            $this->product_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->product_id;
    }

    /**
     * @param int $product_id
     * 
     * @return void
     */
    public function setProduct_id(int $product_id = null)
    {
        $this->product_id = $product_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getTaxon_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->taxon_id === null) {
            $this->taxon_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->taxon_id;
    }

    /**
     * @param int $taxon_id
     * 
     * @return void
     */
    public function setTaxon_id(int $taxon_id = null)
    {
        $this->taxon_id = $taxon_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product|null
     */
    public function get169C6CD94584665A(bool $forceReload = false)
    {
        if ($this->169C6CD94584665A === null || $forceReload) {
            $this->169C6CD94584665A = sylius_productService::getInstance()->getEntityByPrimaryKey($this->product_id);
        }
        return $this->169C6CD94584665A;
    }

    /**
     * @param sylius_product $entity
     * 
     * @return void
     */
    public function set169C6CD94584665A(sylius_product $entity = null)
    {
        $this->169C6CD94584665A = $entity;
        $this->product_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_taxon|null
     */
    public function get169C6CD9DE13F470(bool $forceReload = false)
    {
        if ($this->169C6CD9DE13F470 === null || $forceReload) {
            $this->169C6CD9DE13F470 = sylius_taxonService::getInstance()->getEntityByPrimaryKey($this->taxon_id);
        }
        return $this->169C6CD9DE13F470;
    }

    /**
     * @param sylius_taxon $entity
     * 
     * @return void
     */
    public function set169C6CD9DE13F470(sylius_taxon $entity = null)
    {
        $this->169C6CD9DE13F470 = $entity;
        $this->taxon_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_taxon $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_taxon $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProduct_id() === $entity->getProduct_id() && $this->getTaxon_id() === $entity->getTaxon_id();
    }

}