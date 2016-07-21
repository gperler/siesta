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

class SyliusProductOptionValue implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_option_value";

    const COLUMN_ID = "id";

    const COLUMN_OPTIONID = "option_id";

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
    protected $optionId;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var SyliusProductOption
     */
    protected $F7FF7D4BA7C41D6F;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_option_value_U(" : "CALL sylius_product_option_value_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->optionId) . ',' . Escaper::quoteString($connection, $this->value) . ');';
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
        if ($cascade && $this->F7FF7D4BA7C41D6F !== null) {
            $this->F7FF7D4BA7C41D6F->save($cascade, $cycleDetector, $connectionName);
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
        $this->optionId = $resultSet->getIntegerValue("option_id");
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
        $connection->execute("CALL sylius_product_option_value_DB_PK($id)");
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
        $this->setOptionId($arrayAccessor->getIntegerValue("optionId"));
        $this->setValue($arrayAccessor->getStringValue("value"));
        $this->_existing = ($this->id !== null);
        $F7FF7D4BA7C41D6FArray = $arrayAccessor->getArray("F7FF7D4BA7C41D6F");
        if ($F7FF7D4BA7C41D6FArray !== null) {
            $F7FF7D4BA7C41D6F = SyliusProductOptionService::getInstance()->newInstance();
            $F7FF7D4BA7C41D6F->fromArray($F7FF7D4BA7C41D6FArray);
            $this->setF7FF7D4BA7C41D6F($F7FF7D4BA7C41D6F);
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
            "optionId" => $this->getOptionId(),
            "value" => $this->getValue()
        ];
        if ($this->F7FF7D4BA7C41D6F !== null) {
            $result["F7FF7D4BA7C41D6F"] = $this->F7FF7D4BA7C41D6F->toArray($cycleDetector);
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
    public function getOptionId()
    {
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
        $this->value = StringUtil::trimToNull($value, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductOption|null
     */
    public function getF7FF7D4BA7C41D6F(bool $forceReload = false)
    {
        if ($this->F7FF7D4BA7C41D6F === null || $forceReload) {
            $this->F7FF7D4BA7C41D6F = SyliusProductOptionService::getInstance()->getEntityByPrimaryKey($this->optionId);
        }
        return $this->F7FF7D4BA7C41D6F;
    }

    /**
     * @param SyliusProductOption $entity
     * 
     * @return void
     */
    public function setF7FF7D4BA7C41D6F(SyliusProductOption $entity = null)
    {
        $this->F7FF7D4BA7C41D6F = $entity;
        $this->optionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductOptionValue $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductOptionValue $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}