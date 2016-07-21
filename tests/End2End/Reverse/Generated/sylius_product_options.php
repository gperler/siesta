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

class sylius_product_options implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_options";

    const COLUMN_PRODUCT_ID = "product_id";

    const COLUMN_OPTION_ID = "option_id";

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
    protected $option_id;

    /**
     * @var sylius_product
     */
    protected $2B5FF0094584665A;

    /**
     * @var sylius_product_option
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
        $this->getProduct_id(true, $connectionName);
        $this->getOption_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->product_id) . ',' . Escaper::quoteInt($this->option_id) . ');';
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
        $this->product_id = $resultSet->getIntegerValue("product_id");
        $this->option_id = $resultSet->getIntegerValue("option_id");
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
        $option_id = Escaper::quoteInt($this->option_id);
        $connection->execute("CALL sylius_product_options_DB_PK($product_id,$option_id)");
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
        $this->setOption_id($arrayAccessor->getIntegerValue("option_id"));
        $this->_existing = ($this->product_id !== null) && ($this->option_id !== null);
        $2B5FF0094584665AArray = $arrayAccessor->getArray("2B5FF0094584665A");
        if ($2B5FF0094584665AArray !== null) {
            $2B5FF0094584665A = sylius_productService::getInstance()->newInstance();
            $2B5FF0094584665A->fromArray($2B5FF0094584665AArray);
            $this->set2B5FF0094584665A($2B5FF0094584665A);
        }
        $2B5FF009A7C41D6FArray = $arrayAccessor->getArray("2B5FF009A7C41D6F");
        if ($2B5FF009A7C41D6FArray !== null) {
            $2B5FF009A7C41D6F = sylius_product_optionService::getInstance()->newInstance();
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
            "product_id" => $this->getProduct_id(),
            "option_id" => $this->getOption_id()
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
    public function getOption_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->option_id === null) {
            $this->option_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->option_id;
    }

    /**
     * @param int $option_id
     * 
     * @return void
     */
    public function setOption_id(int $option_id = null)
    {
        $this->option_id = $option_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product|null
     */
    public function get2B5FF0094584665A(bool $forceReload = false)
    {
        if ($this->2B5FF0094584665A === null || $forceReload) {
            $this->2B5FF0094584665A = sylius_productService::getInstance()->getEntityByPrimaryKey($this->product_id);
        }
        return $this->2B5FF0094584665A;
    }

    /**
     * @param sylius_product $entity
     * 
     * @return void
     */
    public function set2B5FF0094584665A(sylius_product $entity = null)
    {
        $this->2B5FF0094584665A = $entity;
        $this->product_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_option|null
     */
    public function get2B5FF009A7C41D6F(bool $forceReload = false)
    {
        if ($this->2B5FF009A7C41D6F === null || $forceReload) {
            $this->2B5FF009A7C41D6F = sylius_product_optionService::getInstance()->getEntityByPrimaryKey($this->option_id);
        }
        return $this->2B5FF009A7C41D6F;
    }

    /**
     * @param sylius_product_option $entity
     * 
     * @return void
     */
    public function set2B5FF009A7C41D6F(sylius_product_option $entity = null)
    {
        $this->2B5FF009A7C41D6F = $entity;
        $this->option_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_options $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_options $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProduct_id() === $entity->getProduct_id() && $this->getOption_id() === $entity->getOption_id();
    }

}