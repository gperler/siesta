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

class SyliusProductVariantOptionValue implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_variant_option_value";

    const COLUMN_VARIANTID = "variant_id";

    const COLUMN_OPTIONVALUEID = "option_value_id";

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
    protected $variantId;

    /**
     * @var int
     */
    protected $optionValueId;

    /**
     * @var SyliusProductVariant
     */
    protected $76CDAFA13B69A9AF;

    /**
     * @var SyliusProductOptionValue
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
        $this->getVariantId(true, $connectionName);
        $this->getOptionValueId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->variantId) . ',' . Escaper::quoteInt($this->optionValueId) . ');';
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
        $this->variantId = $resultSet->getIntegerValue("variant_id");
        $this->optionValueId = $resultSet->getIntegerValue("option_value_id");
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
        $variantId = Escaper::quoteInt($this->variantId);
        $optionValueId = Escaper::quoteInt($this->optionValueId);
        $connection->execute("CALL sylius_product_variant_option_value_DB_PK($variantId,$optionValueId)");
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
        $this->setVariantId($arrayAccessor->getIntegerValue("variantId"));
        $this->setOptionValueId($arrayAccessor->getIntegerValue("optionValueId"));
        $this->_existing = ($this->variantId !== null) && ($this->optionValueId !== null);
        $76CDAFA13B69A9AFArray = $arrayAccessor->getArray("76CDAFA13B69A9AF");
        if ($76CDAFA13B69A9AFArray !== null) {
            $76CDAFA13B69A9AF = SyliusProductVariantService::getInstance()->newInstance();
            $76CDAFA13B69A9AF->fromArray($76CDAFA13B69A9AFArray);
            $this->set76CDAFA13B69A9AF($76CDAFA13B69A9AF);
        }
        $76CDAFA1D957CA06Array = $arrayAccessor->getArray("76CDAFA1D957CA06");
        if ($76CDAFA1D957CA06Array !== null) {
            $76CDAFA1D957CA06 = SyliusProductOptionValueService::getInstance()->newInstance();
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
            "variantId" => $this->getVariantId(),
            "optionValueId" => $this->getOptionValueId()
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
    public function getVariantId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->variantId === null) {
            $this->variantId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->variantId;
    }

    /**
     * @param int $variantId
     * 
     * @return void
     */
    public function setVariantId(int $variantId = null)
    {
        $this->variantId = $variantId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getOptionValueId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->optionValueId === null) {
            $this->optionValueId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->optionValueId;
    }

    /**
     * @param int $optionValueId
     * 
     * @return void
     */
    public function setOptionValueId(int $optionValueId = null)
    {
        $this->optionValueId = $optionValueId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductVariant|null
     */
    public function get76CDAFA13B69A9AF(bool $forceReload = false)
    {
        if ($this->76CDAFA13B69A9AF === null || $forceReload) {
            $this->76CDAFA13B69A9AF = SyliusProductVariantService::getInstance()->getEntityByPrimaryKey($this->variantId);
        }
        return $this->76CDAFA13B69A9AF;
    }

    /**
     * @param SyliusProductVariant $entity
     * 
     * @return void
     */
    public function set76CDAFA13B69A9AF(SyliusProductVariant $entity = null)
    {
        $this->76CDAFA13B69A9AF = $entity;
        $this->variantId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductOptionValue|null
     */
    public function get76CDAFA1D957CA06(bool $forceReload = false)
    {
        if ($this->76CDAFA1D957CA06 === null || $forceReload) {
            $this->76CDAFA1D957CA06 = SyliusProductOptionValueService::getInstance()->getEntityByPrimaryKey($this->optionValueId);
        }
        return $this->76CDAFA1D957CA06;
    }

    /**
     * @param SyliusProductOptionValue $entity
     * 
     * @return void
     */
    public function set76CDAFA1D957CA06(SyliusProductOptionValue $entity = null)
    {
        $this->76CDAFA1D957CA06 = $entity;
        $this->optionValueId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductVariantOptionValue $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductVariantOptionValue $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getVariantId() === $entity->getVariantId() && $this->getOptionValueId() === $entity->getOptionValueId();
    }

}