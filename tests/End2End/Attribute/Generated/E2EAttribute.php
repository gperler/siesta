<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Attribute\Generated;

use SiestaTest\End2End\Util\AttributeSerialize;
use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class E2EAttribute implements ArraySerializable
{

    const TABLE_NAME = "E2EAttribute";

    const COLUMN_ID = "ID";

    const COLUMN_BOOL = "D_BOOLEAN";

    const COLUMN_INT = "D_INTEGER";

    const COLUMN_FLOAT = "D_FLOAT";

    const COLUMN_STRING = "D_STRING";

    const COLUMN_TRANSIENT = "transient";

    const COLUMN_ARRAY = "array";

    const COLUMN_DATETIME = "D_DATETIME";

    const COLUMN_PDATE = "D_DATE";

    const COLUMN_PTIME = "D_TIME";

    const COLUMN_OBJECT = "object";

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
     * @var bool
     */
    protected $bool;

    /**
     * @var int
     */
    protected $int;

    /**
     * @var float
     */
    protected $float;

    /**
     * @var string
     */
    protected $string;

    /**
     * @var string
     */
    protected $transient;

    /**
     * @var array
     */
    protected $array;

    /**
     * @var SiestaDateTime
     */
    protected $dateTime;

    /**
     * @var SiestaDateTime
     */
    protected $pDate;

    /**
     * @var SiestaDateTime
     */
    protected $pTime;

    /**
     * @var AttributeSerialize
     */
    protected $object;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
        $this->bool = true;
        $this->int = 42;
        $this->float = 42.42;
        $this->string = 'Discovery';
        $this->transient = 'Transient';
        $this->array = [ 'x' => 'y' ];
        $this->dateTime = new SiestaDateTime('19-08-1977 10:10:10');
        $this->pDate = new SiestaDateTime('19-08-1977');
        $this->pTime = new SiestaDateTime('10:11:12');
        $this->object = new AttributeSerialize();
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL E2EAttribute_U(" : "CALL E2EAttribute_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteBool($this->bool) . ',' . Escaper::quoteInt($this->int) . ',' . Escaper::quoteFloat($this->float) . ',' . Escaper::quoteString($connection, $this->string) . ',' . Escaper::quoteArray($connection, $this->array) . ',' . Escaper::quoteDateTime($this->dateTime) . ',' . Escaper::quoteDate($this->pDate) . ',' . Escaper::quoteTime($this->pTime) . ',' . Escaper::quoteObject($connection,$this->object) . ');';
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
        $this->id = $resultSet->getIntegerValue("ID");
        $this->bool = $resultSet->getBooleanValue("D_BOOLEAN");
        $this->int = $resultSet->getIntegerValue("D_INTEGER");
        $this->float = $resultSet->getFloatValue("D_FLOAT");
        $this->string = $resultSet->getStringValue("D_STRING");
        $this->transient = $resultSet->getStringValue("transient");
        $this->array = $resultSet->getArray("array");
        $this->dateTime = $resultSet->getDateTime("D_DATETIME");
        $this->pDate = $resultSet->getDateTime("D_DATE");
        $this->pTime = $resultSet->getDateTime("D_TIME");
        $this->object = $resultSet->getObject("object");
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
        $connection->execute("CALL E2EAttribute_DB_PK($id)");
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
        $this->setBool($arrayAccessor->getBooleanValue("bool"));
        $this->setInt($arrayAccessor->getIntegerValue("int"));
        $this->setFloat($arrayAccessor->getFloatValue("float"));
        $this->setString($arrayAccessor->getStringValue("string"));
        $this->setTransient($arrayAccessor->getStringValue("transient"));
        $this->setArray($arrayAccessor->getArray("array"));
        $this->setDateTime($arrayAccessor->getDateTime("dateTime"));
        $this->setPDate($arrayAccessor->getDateTime("pDate"));
        $this->setPTime($arrayAccessor->getDateTime("pTime"));
        $objectArray = $arrayAccessor->getArray("object");
        if ($objectArray !== null) {
            $object = new AttributeSerialize();
            $object->fromArray($objectArray);
            $this->setObject($object);
        }
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
            "bool" => $this->getBool(),
            "int" => $this->getInt(),
            "float" => $this->getFloat(),
            "string" => $this->getString(),
            "transient" => $this->getTransient(),
            "array" => $this->getArray(),
            "dateTime" => ($this->getDateTime() !== null) ? $this->getDateTime()->getJSONDateTime() : null,
            "pDate" => ($this->getPDate() !== null) ? $this->getPDate()->getJSONDateTime() : null,
            "pTime" => ($this->getPTime() !== null) ? $this->getPTime()->getJSONDateTime() : null,
            "object" => ($this->getObject() !== null) ? $this->getObject()->toArray() : null
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
     * @return bool|null
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * @param bool $bool
     * 
     * @return void
     */
    public function setBool(bool $bool = null)
    {
        $this->bool = $bool;
    }

    /**
     * 
     * @return int|null
     */
    public function getInt()
    {
        return $this->int;
    }

    /**
     * @param int $int
     * 
     * @return void
     */
    public function setInt(int $int = null)
    {
        $this->int = $int;
    }

    /**
     * 
     * @return float|null
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * @param float $float
     * 
     * @return void
     */
    public function setFloat(float $float = null)
    {
        $this->float = $float;
    }

    /**
     * 
     * @return string|null
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     * 
     * @return void
     */
    public function setString(string $string = null)
    {
        $this->string = StringUtil::trimToNull($string, 100);
    }

    /**
     * 
     * @return string|null
     */
    public function getTransient()
    {
        return $this->transient;
    }

    /**
     * @param string $transient
     * 
     * @return void
     */
    public function setTransient(string $transient = null)
    {
        $this->transient = StringUtil::trimToNull($transient, null);
    }

    /**
     * 
     * @return array|null
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function setArray(array $array = null)
    {
        $this->array = $array;
    }

    /**
     * @param string $key
     * @param  $value
     * 
     * @return void
     */
    public function addToArray(string $key,  $value = null)
    {
        if ($this->array === null) {
            $this->array = [];
        }
        $this->array[$key] = $value;
    }

    /**
     * @param string $key
     * 
     * @return mixed|null
     */
    public function getFromArray(string $key)
    {
        return ArrayUtil::getFromArray($this->array, $key);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param SiestaDateTime $dateTime
     * 
     * @return void
     */
    public function setDateTime(SiestaDateTime $dateTime = null)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getPDate()
    {
        return $this->pDate;
    }

    /**
     * @param SiestaDateTime $pDate
     * 
     * @return void
     */
    public function setPDate(SiestaDateTime $pDate = null)
    {
        $this->pDate = $pDate;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getPTime()
    {
        return $this->pTime;
    }

    /**
     * @param SiestaDateTime $pTime
     * 
     * @return void
     */
    public function setPTime(SiestaDateTime $pTime = null)
    {
        $this->pTime = $pTime;
    }

    /**
     * 
     * @return AttributeSerialize|null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param AttributeSerialize $object
     * 
     * @return void
     */
    public function setObject(AttributeSerialize $object = null)
    {
        $this->object = $object;
    }

    /**
     * @param E2EAttribute $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(E2EAttribute $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}