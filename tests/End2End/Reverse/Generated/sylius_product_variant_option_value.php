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

class sylius_product_variant_option_value implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_variant_option_value";

    const COLUMN_VARIANT_ID = "variant_id";

    const COLUMN_OPTION_VALUE_ID = "option_value_id";

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
    protected $variant_id;

    /**
     * @var int
     */
    protected $option_value_id;

    /**
     * @var sylius_product_variant
     */
    protected $76CDAFA13B69A9AF;

    /**
     * @var sylius_product_option_value
     */
    protected $76CDAFA1D957CA06;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_variant_option_value_U(" : "CALL sylius_product_variant_option_value_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getVariant_id(true, $connectionName);
        $this->getOption_value_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->variant_id) . ',' . Escaper::quoteInt($this->option_value_id) . ');';
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
        if ($cascade && $this->76CDAFA13B69A9AF !== null) {
            $this->76CDAFA13B69A9AF->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->76CDAFA1D957CA06 !== null) {
            $this->76CDAFA1D957CA06->save($cascade, $cycleDetector, $connectionName);
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
        $this->variant_id = $resultSet->getIntegerValue("variant_id");
        $this->option_value_id = $resultSet->getIntegerValue("option_value_id");
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
        $variant_id = Escaper::quoteInt($this->variant_id);
        $option_value_id = Escaper::quoteInt($this->option_value_id);
        $connection->execute("CALL sylius_product_variant_option_value_DB_PK($variant_id,$option_value_id)");
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
        $this->setVariant_id($arrayAccessor->getIntegerValue("variant_id"));
        $this->setOption_value_id($arrayAccessor->getIntegerValue("option_value_id"));
        $this->_existing = ($this->variant_id !== null) && ($this->option_value_id !== null);
        $76CDAFA13B69A9AFArray = $arrayAccessor->getArray("76CDAFA13B69A9AF");
        if ($76CDAFA13B69A9AFArray !== null) {
            $76CDAFA13B69A9AF = sylius_product_variantService::getInstance()->newInstance();
            $76CDAFA13B69A9AF->fromArray($76CDAFA13B69A9AFArray);
            $this->set76CDAFA13B69A9AF($76CDAFA13B69A9AF);
        }
        $76CDAFA1D957CA06Array = $arrayAccessor->getArray("76CDAFA1D957CA06");
        if ($76CDAFA1D957CA06Array !== null) {
            $76CDAFA1D957CA06 = sylius_product_option_valueService::getInstance()->newInstance();
            $76CDAFA1D957CA06->fromArray($76CDAFA1D957CA06Array);
            $this->set76CDAFA1D957CA06($76CDAFA1D957CA06);
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
            "variant_id" => $this->getVariant_id(),
            "option_value_id" => $this->getOption_value_id()
        ];
        if ($this->76CDAFA13B69A9AF !== null) {
            $result["76CDAFA13B69A9AF"] = $this->76CDAFA13B69A9AF->toArray($cycleDetector);
        }
        if ($this->76CDAFA1D957CA06 !== null) {
            $result["76CDAFA1D957CA06"] = $this->76CDAFA1D957CA06->toArray($cycleDetector);
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
    public function getVariant_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->variant_id === null) {
            $this->variant_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->variant_id;
    }

    /**
     * @param int $variant_id
     * 
     * @return void
     */
    public function setVariant_id(int $variant_id = null)
    {
        $this->variant_id = $variant_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getOption_value_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->option_value_id === null) {
            $this->option_value_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->option_value_id;
    }

    /**
     * @param int $option_value_id
     * 
     * @return void
     */
    public function setOption_value_id(int $option_value_id = null)
    {
        $this->option_value_id = $option_value_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_variant|null
     */
    public function get76CDAFA13B69A9AF(bool $forceReload = false)
    {
        if ($this->76CDAFA13B69A9AF === null || $forceReload) {
            $this->76CDAFA13B69A9AF = sylius_product_variantService::getInstance()->getEntityByPrimaryKey($this->variant_id);
        }
        return $this->76CDAFA13B69A9AF;
    }

    /**
     * @param sylius_product_variant $entity
     * 
     * @return void
     */
    public function set76CDAFA13B69A9AF(sylius_product_variant $entity = null)
    {
        $this->76CDAFA13B69A9AF = $entity;
        $this->variant_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_option_value|null
     */
    public function get76CDAFA1D957CA06(bool $forceReload = false)
    {
        if ($this->76CDAFA1D957CA06 === null || $forceReload) {
            $this->76CDAFA1D957CA06 = sylius_product_option_valueService::getInstance()->getEntityByPrimaryKey($this->option_value_id);
        }
        return $this->76CDAFA1D957CA06;
    }

    /**
     * @param sylius_product_option_value $entity
     * 
     * @return void
     */
    public function set76CDAFA1D957CA06(sylius_product_option_value $entity = null)
    {
        $this->76CDAFA1D957CA06 = $entity;
        $this->option_value_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_variant_option_value $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_variant_option_value $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getVariant_id() === $entity->getVariant_id() && $this->getOption_value_id() === $entity->getOption_value_id();
    }

}