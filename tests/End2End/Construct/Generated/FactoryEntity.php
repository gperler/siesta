<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Construct\Generated;

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

class FactoryEntity implements ArraySerializable
{

    const TABLE_NAME = "FactoryEntity";

    const DELIMIT_TABLE_NAME = "FactoryEntity";

    const COLUMN_ID1 = "id1";

    const COLUMN_BOOL1 = "bool1";

    const COLUMN_INT1 = "int1";

    const COLUMN_FLOAT1 = "float1";

    const COLUMN_STRING1 = "string1";

    const COLUMN_DATETIME1 = "dateTime1";

    const COLUMN_DATE1 = "date1";

    const COLUMN_TIME1 = "time1";

    const COLUMN_OBJECT1 = "object1";

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
    protected $id1;

    /**
     * @var bool
     */
    protected $bool1;

    /**
     * @var int
     */
    protected $int1;

    /**
     * @var float
     */
    protected $float1;

    /**
     * @var string
     */
    protected $string1;

    /**
     * @var SiestaDateTime
     */
    protected $dateTime1;

    /**
     * @var SiestaDateTime
     */
    protected $date1;

    /**
     * @var SiestaDateTime
     */
    protected $time1;

    /**
     * @var AttributeSerialize
     */
    protected $object1;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
        $this->object1 = new AttributeSerialize();
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL FactoryEntity_U(" : "CALL FactoryEntity_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId1(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id1) . ',' . Escaper::quoteBool($this->bool1) . ',' . Escaper::quoteInt($this->int1) . ',' . Escaper::quoteFloat($this->float1) . ',' . Escaper::quoteString($connection, $this->string1) . ',' . Escaper::quoteDateTime($this->dateTime1) . ',' . Escaper::quoteDate($this->date1) . ',' . Escaper::quoteTime($this->time1) . ',' . Escaper::quoteObject($connection,$this->object1) . ');';
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
        $this->id1 = $resultSet->getIntegerValue("id1");
        $this->bool1 = $resultSet->getBooleanValue("bool1");
        $this->int1 = $resultSet->getIntegerValue("int1");
        $this->float1 = $resultSet->getFloatValue("float1");
        $this->string1 = $resultSet->getStringValue("string1");
        $this->dateTime1 = $resultSet->getDateTime("dateTime1");
        $this->date1 = $resultSet->getDateTime("date1");
        $this->time1 = $resultSet->getDateTime("time1");
        $this->object1 = $resultSet->getObject("object1");
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
        $id1 = Escaper::quoteInt($this->id1);
        $connection->execute("CALL FactoryEntity_DB_PK($id1)");
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
        $this->setId1($arrayAccessor->getIntegerValue("id1"));
        $this->setBool1($arrayAccessor->getBooleanValue("bool1"));
        $this->setInt1($arrayAccessor->getIntegerValue("int1"));
        $this->setFloat1($arrayAccessor->getFloatValue("float1"));
        $this->setString1($arrayAccessor->getStringValue("string1"));
        $this->setDateTime1($arrayAccessor->getDateTime("dateTime1"));
        $this->setDate1($arrayAccessor->getDateTime("date1"));
        $this->setTime1($arrayAccessor->getDateTime("time1"));
        $object1Array = $arrayAccessor->getArray("object1");
        if ($object1Array !== null) {
            $object1 = new AttributeSerialize();
            $object1->fromArray($object1Array);
            $this->setObject1($object1);
        }
        $this->_existing = ($this->id1 !== null);
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
            "id1" => $this->getId1(),
            "bool1" => $this->getBool1(),
            "int1" => $this->getInt1(),
            "float1" => $this->getFloat1(),
            "string1" => $this->getString1(),
            "dateTime1" => ($this->getDateTime1() !== null) ? $this->getDateTime1()->getJSONDateTime() : null,
            "date1" => ($this->getDate1() !== null) ? $this->getDate1()->getJSONDateTime() : null,
            "time1" => ($this->getTime1() !== null) ? $this->getTime1()->getJSONDateTime() : null,
            "object1" => ($this->getObject1() !== null) ? $this->getObject1()->toArray() : null
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
    public function getId1(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id1 === null) {
            $this->id1 = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id1;
    }

    /**
     * @param int $id1
     * 
     * @return void
     */
    public function setId1(int $id1 = null)
    {
        $this->id1 = $id1;
    }

    /**
     * 
     * @return bool|null
     */
    public function getBool1()
    {
        return $this->bool1;
    }

    /**
     * @param bool $bool1
     * 
     * @return void
     */
    public function setBool1(bool $bool1 = null)
    {
        $this->bool1 = $bool1;
    }

    /**
     * 
     * @return int|null
     */
    public function getInt1()
    {
        return $this->int1;
    }

    /**
     * @param int $int1
     * 
     * @return void
     */
    public function setInt1(int $int1 = null)
    {
        $this->int1 = $int1;
    }

    /**
     * 
     * @return float|null
     */
    public function getFloat1()
    {
        return $this->float1;
    }

    /**
     * @param float $float1
     * 
     * @return void
     */
    public function setFloat1(float $float1 = null)
    {
        $this->float1 = $float1;
    }

    /**
     * 
     * @return string|null
     */
    public function getString1()
    {
        return $this->string1;
    }

    /**
     * @param string $string1
     * 
     * @return void
     */
    public function setString1(string $string1 = null)
    {
        $this->string1 = StringUtil::trimToNull($string1, 100);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getDateTime1()
    {
        return $this->dateTime1;
    }

    /**
     * @param SiestaDateTime $dateTime1
     * 
     * @return void
     */
    public function setDateTime1(SiestaDateTime $dateTime1 = null)
    {
        $this->dateTime1 = $dateTime1;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getDate1()
    {
        return $this->date1;
    }

    /**
     * @param SiestaDateTime $date1
     * 
     * @return void
     */
    public function setDate1(SiestaDateTime $date1 = null)
    {
        $this->date1 = $date1;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getTime1()
    {
        return $this->time1;
    }

    /**
     * @param SiestaDateTime $time1
     * 
     * @return void
     */
    public function setTime1(SiestaDateTime $time1 = null)
    {
        $this->time1 = $time1;
    }

    /**
     * 
     * @return AttributeSerialize|null
     */
    public function getObject1()
    {
        return $this->object1;
    }

    /**
     * @param AttributeSerialize $object1
     * 
     * @return void
     */
    public function setObject1(AttributeSerialize $object1 = null)
    {
        $this->object1 = $object1;
    }

    /**
     * @param FactoryEntity $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(FactoryEntity $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId1() === $entity->getId1();
    }

}