<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;

class ProductRelated implements ArraySerializable
{

    const TABLE_NAME = "ProductRelated";

    const COLUMN_PRODUCTSOURCEID = "FK_source";

    const COLUMN_PRODUCTTARGETID = "FK_target";

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
    protected $productSourceId;

    /**
     * @var int
     */
    protected $productTargetId;

    /**
     * @var Product
     */
    protected $productSource;

    /**
     * @var Product
     */
    protected $productTarget;

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
        $spCall = ($this->_existing) ? "CALL ProductRelated_U(" : "CALL ProductRelated_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        return $spCall . Escaper::quoteInt($this->productSourceId) . ',' . Escaper::quoteInt($this->productTargetId) . ');';
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
        if ($cascade && $this->productSource !== null) {
            $this->productSource->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->productTarget !== null) {
            $this->productTarget->save($cascade, $cycleDetector, $connectionName);
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
        $this->productSourceId = $resultSet->getIntegerValue("FK_source");
        $this->productTargetId = $resultSet->getIntegerValue("FK_target");
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
        $connection->execute("CALL ProductRelated_DB_PK()");
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
        $this->setProductSourceId($arrayAccessor->getIntegerValue("productSourceId"));
        $this->setProductTargetId($arrayAccessor->getIntegerValue("productTargetId"));
        $productSourceArray = $arrayAccessor->getArray("productSource");
        if ($productSourceArray !== null) {
            $productSource = ProductService::getInstance()->newInstance();
            $productSource->fromArray($productSourceArray);
            $this->setProductSource($productSource);
        }
        $productTargetArray = $arrayAccessor->getArray("productTarget");
        if ($productTargetArray !== null) {
            $productTarget = ProductService::getInstance()->newInstance();
            $productTarget->fromArray($productTargetArray);
            $this->setProductTarget($productTarget);
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
            "productSourceId" => $this->getProductSourceId(),
            "productTargetId" => $this->getProductTargetId()
        ];
        if ($this->productSource !== null) {
            $result["productSource"] = $this->productSource->toArray($cycleDetector);
        }
        if ($this->productTarget !== null) {
            $result["productTarget"] = $this->productTarget->toArray($cycleDetector);
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
     * 
     * @return int|null
     */
    public function getProductSourceId()
    {
        return $this->productSourceId;
    }

    /**
     * @param int $productSourceId
     * 
     * @return void
     */
    public function setProductSourceId(int $productSourceId = null)
    {
        $this->productSourceId = $productSourceId;
    }

    /**
     * 
     * @return int|null
     */
    public function getProductTargetId()
    {
        return $this->productTargetId;
    }

    /**
     * @param int $productTargetId
     * 
     * @return void
     */
    public function setProductTargetId(int $productTargetId = null)
    {
        $this->productTargetId = $productTargetId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return Product|null
     */
    public function getProductSource(bool $forceReload = false)
    {
        if ($this->productSource === null || $forceReload) {
            $this->productSource = ProductService::getInstance()->getEntityByPrimaryKey($this->productSourceId);
        }
        return $this->productSource;
    }

    /**
     * @param Product $entity
     * 
     * @return void
     */
    public function setProductSource(Product $entity = null)
    {
        $this->productSource = $entity;
        $this->productSourceId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return Product|null
     */
    public function getProductTarget(bool $forceReload = false)
    {
        if ($this->productTarget === null || $forceReload) {
            $this->productTarget = ProductService::getInstance()->getEntityByPrimaryKey($this->productTargetId);
        }
        return $this->productTarget;
    }

    /**
     * @param Product $entity
     * 
     * @return void
     */
    public function setProductTarget(Product $entity = null)
    {
        $this->productTarget = $entity;
        $this->productTargetId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param ProductRelated $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(ProductRelated $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return false;
    }

}