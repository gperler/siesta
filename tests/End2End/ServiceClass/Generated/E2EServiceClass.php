<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\ServiceClass\Generated;

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

class E2EServiceClass implements ArraySerializable
{

    const TABLE_NAME = "E2EServiceClass";

    const COLUMN_ID = "id";

    const COLUMN_BOOL = "bool";

    const COLUMN_INT = "int";

    const COLUMN_FLOAT = "float";

    const COLUMN_STRING = "string";

    const COLUMN_DATETIME = "dateTime";

    const COLUMN_PDATE = "pDate";

    const COLUMN_PTIME = "pTime";

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
     * @var string
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
        $spCall = ($this->_existing) ? "CALL E2EServiceClass_U(" : "CALL E2EServiceClass_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->id) . ',' . Escaper::quoteBool($this->bool) . ',' . Escaper::quoteInt($this->int) . ',' . Escaper::quoteFloat($this->float) . ',' . Escaper::quoteString($connection, $this->string) . ',' . Escaper::quoteDateTime($this->dateTime) . ',' . Escaper::quoteDate($this->pDate) . ',' . Escaper::quoteTime($this->pTime) . ',' . Escaper::quoteObject($connection,$this->object) . ');';
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
        $this->id = $resultSet->getStringValue("id");
        $this->bool = $resultSet->getBooleanValue("bool");
        $this->int = $resultSet->getIntegerValue("int");
        $this->float = $resultSet->getFloatValue("float");
        $this->string = $resultSet->getStringValue("string");
        $this->dateTime = $resultSet->getDateTime("dateTime");
        $this->pDate = $resultSet->getDateTime("pDate");
        $this->pTime = $resultSet->getDateTime("pTime");
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
        $id = Escaper::quoteString($connection, $this->id);
        $connection->execute("CALL E2EServiceClass_DB_PK($id)");
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
        $this->setId($arrayAccessor->getStringValue("id"));
        $this->setBool($arrayAccessor->getBooleanValue("bool"));
        $this->setInt($arrayAccessor->getIntegerValue("int"));
        $this->setFloat($arrayAccessor->getFloatValue("float"));
        $this->setString($arrayAccessor->getStringValue("string"));
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
     * @return string|null
     */
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param string $id
     * 
     * @return void
     */
    public function setId(string $id = null)
    {
        $this->id = StringUtil::trimToNull($id, 36);
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
     * @param E2EServiceClass $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(E2EServiceClass $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}