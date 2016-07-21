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

class sylius_product_option_value implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_option_value";

    const COLUMN_ID = "id";

    const COLUMN_OPTION_ID = "option_id";

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
    protected $option_id;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var sylius_product_option
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->option_id) . ',' . Escaper::quoteString($connection, $this->value) . ');';
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
        $this->option_id = $resultSet->getIntegerValue("option_id");
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
        $this->setOption_id($arrayAccessor->getIntegerValue("option_id"));
        $this->setValue($arrayAccessor->getStringValue("value"));
        $this->_existing = ($this->id !== null);
        $F7FF7D4BA7C41D6FArray = $arrayAccessor->getArray("F7FF7D4BA7C41D6F");
        if ($F7FF7D4BA7C41D6FArray !== null) {
            $F7FF7D4BA7C41D6F = sylius_product_optionService::getInstance()->newInstance();
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
            "option_id" => $this->getOption_id(),
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
    public function getOption_id()
    {
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
     * @return sylius_product_option|null
     */
    public function getF7FF7D4BA7C41D6F(bool $forceReload = false)
    {
        if ($this->F7FF7D4BA7C41D6F === null || $forceReload) {
            $this->F7FF7D4BA7C41D6F = sylius_product_optionService::getInstance()->getEntityByPrimaryKey($this->option_id);
        }
        return $this->F7FF7D4BA7C41D6F;
    }

    /**
     * @param sylius_product_option $entity
     * 
     * @return void
     */
    public function setF7FF7D4BA7C41D6F(sylius_product_option $entity = null)
    {
        $this->F7FF7D4BA7C41D6F = $entity;
        $this->option_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_option_value $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_option_value $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}