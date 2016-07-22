<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

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

class Product implements ArraySerializable
{

    const TABLE_NAME = "Product";

    const COLUMN_ID = "id";

    const COLUMN_NAME = "name";

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
     * @var string
     */
    protected $name;

    /**
     * @var Product[]
     */
    protected $relatedProductList;

    /**
     * @var ProductRelated[]
     */
    protected $relatedProductListMapping;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
        $this->relatedProductListMapping = [];
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL Product_U(" : "CALL Product_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->name) . ');';
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
        foreach ($this->relatedProductListMapping as $mapping) {
            $mapping->save($cascade, $cycleDetector, $connectionName);
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
        $this->name = $resultSet->getStringValue("name");
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
        $connection->execute("CALL Product_DB_PK($id)");
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
        $this->setName($arrayAccessor->getStringValue("name"));
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
            "name" => $this->getName()
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
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name = null)
    {
        $this->name = StringUtil::trimToNull($name, 30);
    }

    /**
     * @param bool $forceReload
     * @param string $connectionName
     * 
     * @return Product[]
     */
    public function getRelatedProductList(bool $forceReload = false, string $connectionName = null) : array
    {
        if ($this->relatedProductList === null || $forceReload) {
            $this->relatedProductList = ProductService::getInstance()->getProductJoinProductRelated($this->id, $connectionName);
        }
        return $this->relatedProductList;
    }

    /**
     * @param Product $entity
     * 
     * @return void
     */
    public function addToRelatedProductList(Product $entity)
    {
        $mapping = ProductRelatedService::getInstance()->newInstance();
        $mapping->setProductSource($this);
        $mapping->setProductTarget($entity);
        $this->relatedProductListMapping[] = $mapping;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteFromRelatedProductList(int $id = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $localProductId = Escaper::quoteInt($this->id);
        $foreignProductId = Escaper::quoteInt($id);
        $connection->execute("CALL ProductRelated_D_A_Product_relatedProductList($localProductId,$foreignProductId)");
        if ($id === null) {
            $this->relatedProductList = [];
            $this->relatedProductListMapping = [];
            return;
        }
        if ($this->relatedProductList !== null) {
            foreach ($this->relatedProductList as $index => $entity) {
                if ($id === $entity->getId()) {
                    array_splice($this->relatedProductList, $index, 1);
                    break;
                }
            }
        }
        if ($this->relatedProductListMapping !== null) {
            foreach ($this->relatedProductListMapping as $index => $mapping) {
                if ($mapping->getProductTargetId() === $id) {
                    array_splice($this->relatedProductListMapping, $index, 1);
                    break;
                }
            }
        }
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteAssignedProduct(int $id = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $localProductId = Escaper::quoteInt($this->id);
        $foreignProductId = Escaper::quoteInt($id);
        $connection->execute("CALL Product_D_JOIN_ProductRelated_relatedProductList($localProductId,$foreignProductId)");
        if ($id === null) {
            $this->relatedProductList = [];
            $this->relatedProductListMapping = [];
            return;
        }
        if ($this->relatedProductList !== null) {
            foreach ($this->relatedProductList as $index => $entity) {
                if ($id === $entity->getId()) {
                    array_splice($this->relatedProductList, $index, 1);
                    break;
                }
            }
        }
        if ($this->relatedProductListMapping !== null) {
            foreach ($this->relatedProductListMapping as $index => $mapping) {
                if ($mapping->getProductTargetId() === $id) {
                    array_splice($this->relatedProductListMapping, $index, 1);
                    break;
                }
            }
        }
    }

    /**
     * @param Product $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(Product $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}