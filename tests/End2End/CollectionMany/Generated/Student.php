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

class Student implements ArraySerializable
{

    const TABLE_NAME = "Student";

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
     * @var Exam[]
     */
    protected $examList;

    /**
     * @var StudentExam[]
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
        $spCall = ($this->_existing) ? "CALL Student_U(" : "CALL Student_I(";
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
        $connection->execute("CALL Student_DB_PK($id)");
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
     * @return Exam[]
     */
    public function getExamList(bool $forceReload = false, string $connectionName = null) : array
    {
        if ($this->examList === null || $forceReload) {
            $this->examList = ExamService::getInstance()->getExamJoinStudentExam($this->id, $connectionName);
        }
        return $this->examList;
    }

    /**
     * @param Exam $entity
     * 
     * @return void
     */
    public function addToExamList(Exam $entity)
    {
        $mapping = StudentExamService::getInstance()->newInstance();
        $mapping->setStudentReference($this);
        $mapping->setExamReference($entity);
        $this->examListMapping[] = $mapping;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteFromExamList(int $id = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $localStudentId = Escaper::quoteInt($this->id);
        $foreignExamId = Escaper::quoteInt($id);
        $connection->execute("CALL StudentExam_D_A_Student_examList($localStudentId,$foreignExamId)");
        if ($id === null) {
            $this->examList = [];
            $this->examListMapping = [];
            return;
        }
        if ($this->examList !== null) {
            foreach ($this->examList as $index => $entity) {
                if ($id === $entity->getId()) {
                    array_splice($this->examList, $index, 1);
                    break;
                }
            }
        }
        if ($this->examListMapping !== null) {
            foreach ($this->examListMapping as $index => $mapping) {
                if ($mapping->getExamId() === $id) {
                    array_splice($this->examListMapping, $index, 1);
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
    public function deleteAssignedExam(int $id = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $localStudentId = Escaper::quoteInt($this->id);
        $foreignExamId = Escaper::quoteInt($id);
        $connection->execute("CALL Exam_D_JOIN_StudentExam_examList($localStudentId,$foreignExamId)");
        if ($id === null) {
            $this->examList = [];
            $this->examListMapping = [];
            return;
        }
        if ($this->examList !== null) {
            foreach ($this->examList as $index => $entity) {
                if ($id === $entity->getId()) {
                    array_splice($this->examList, $index, 1);
                    break;
                }
            }
        }
        if ($this->examListMapping !== null) {
            foreach ($this->examListMapping as $index => $mapping) {
                if ($mapping->getExamId() === $id) {
                    array_splice($this->examListMapping, $index, 1);
                    break;
                }
            }
        }
    }

    /**
     * @param Student $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(Student $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}