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

class StudentMPK implements ArraySerializable
{

    const TABLE_NAME = "StudentMPK";

    const COLUMN_ID1 = "id1";

    const COLUMN_ID2 = "id2";

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
     * @var string
     */
    protected $id1;

    /**
     * @var string
     */
    protected $id2;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ExamMPK[]
     */
    protected $examList;

    /**
     * @var StudentExamMPK[]
     */
    protected $examListMapping;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
        $this->examListMapping = [];
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL StudentMPK_U(" : "CALL StudentMPK_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId1(true, $connectionName);
        $this->getId2(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->id1) . ',' . Escaper::quoteString($connection, $this->id2) . ',' . Escaper::quoteString($connection, $this->name) . ');';
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
        foreach ($this->examListMapping as $mapping) {
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
        $this->id1 = $resultSet->getStringValue("id1");
        $this->id2 = $resultSet->getStringValue("id2");
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
        $id1 = Escaper::quoteString($connection, $this->id1);
        $id2 = Escaper::quoteString($connection, $this->id2);
        $connection->execute("CALL StudentMPK_DB_PK($id1,$id2)");
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
        $this->setId1($arrayAccessor->getStringValue("id1"));
        $this->setId2($arrayAccessor->getStringValue("id2"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->_existing = ($this->id1 !== null) && ($this->id2 !== null);
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
            "id2" => $this->getId2(),
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
     * @return string|null
     */
    public function getId1(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id1 === null) {
            $this->id1 = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id1;
    }

    /**
     * @param string $id1
     * 
     * @return void
     */
    public function setId1(string $id1 = null)
    {
        $this->id1 = StringUtil::trimToNull($id1, 36);
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return string|null
     */
    public function getId2(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id2 === null) {
            $this->id2 = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id2;
    }

    /**
     * @param string $id2
     * 
     * @return void
     */
    public function setId2(string $id2 = null)
    {
        $this->id2 = StringUtil::trimToNull($id2, 36);
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
     * @return ExamMPK[]
     */
    public function getExamList(bool $forceReload = false, string $connectionName = null) : array
    {
        if ($this->examList === null || $forceReload) {
            $this->examList = ExamMPKService::getInstance()->getExamMPKJoinStudentExamMPK($this->id1, $this->id2, $connectionName);
        }
        return $this->examList;
    }

    /**
     * @param ExamMPK $entity
     * 
     * @return void
     */
    public function addToExamList(ExamMPK $entity)
    {
        $mapping = StudentExamMPKService::getInstance()->newInstance();
        $mapping->setStudentReference($this);
        $mapping->setExamReference($entity);
        $this->examListMapping[] = $mapping;
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteFromExamList(string $id1 = null, string $id2 = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $studentMPKId1 = Escaper::quoteString($connection, $this->id1);
        $studentMPKId2 = Escaper::quoteString($connection, $this->id2);
        $examMPKId1 = Escaper::quoteString($connection, $id1);
        $examMPKId2 = Escaper::quoteString($connection, $id2);
        $connection->execute("CALL StudentExamMPK_D_A_StudentMPK_examList($studentMPKId1,$studentMPKId2,$examMPKId1,$examMPKId2)");
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteAssignedExamMPK(string $id1 = null, string $id2 = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $studentMPKId1 = Escaper::quoteString($connection, $this->id1);
        $studentMPKId2 = Escaper::quoteString($connection, $this->id2);
        $examMPKId1 = Escaper::quoteString($connection, $id1);
        $examMPKId2 = Escaper::quoteString($connection, $id2);
        $connection->execute("CALL ExamMPK_D_JOIN_StudentExamMPK_examList($studentMPKId1,$studentMPKId2,$examMPKId1,$examMPKId2)");
    }

    /**
     * @param StudentMPK $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(StudentMPK $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId1() === $entity->getId1() && $this->getId2() === $entity->getId2();
    }

}