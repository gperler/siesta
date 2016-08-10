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

class Exam implements ArraySerializable
{

    const TABLE_NAME = "Exam";

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
     * @var Student[]
     */
    protected $studentList;

    /**
     * @var StudentExam[]
     */
    protected $studentListMapping;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
        $this->studentListMapping = [];
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL Exam_U(" : "CALL Exam_I(";
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
        foreach ($this->studentListMapping as $mapping) {
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
        $connection->execute("CALL Exam_DB_PK($id)");
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
     * @return Student[]
     */
    public function getStudentList(bool $forceReload = false, string $connectionName = null) : array
    {
        if ($this->studentList === null || $forceReload) {
            $this->studentList = StudentService::getInstance()->getStudentJoinStudentExam($this->id, $connectionName);
        }
        return $this->studentList;
    }

    /**
     * @param Student $entity
     * 
     * @return void
     */
    public function addToStudentList(Student $entity)
    {
        $mapping = StudentExamService::getInstance()->newInstance();
        $mapping->setExamReference($this);
        $mapping->setStudentReference($entity);
        $this->studentListMapping[] = $mapping;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteFromStudentList(int $id = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $localExamId = Escaper::quoteInt($this->id);
        $foreignStudentId = Escaper::quoteInt($id);
        $connection->execute("CALL StudentExam_D_A_Exam_studentList($localExamId,$foreignStudentId)");
        if ($id === null) {
            $this->studentList = [];
            $this->studentListMapping = [];
            return;
        }
        if ($this->studentList !== null) {
            foreach ($this->studentList as $index => $entity) {
                if ($id === $entity->getId()) {
                    array_splice($this->studentList, $index, 1);
                    break;
                }
            }
        }
        if ($this->studentListMapping !== null) {
            foreach ($this->studentListMapping as $index => $mapping) {
                if ($mapping->getStudentId() === $id) {
                    array_splice($this->studentListMapping, $index, 1);
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
    public function deleteAssignedStudent(int $id = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $localExamId = Escaper::quoteInt($this->id);
        $foreignStudentId = Escaper::quoteInt($id);
        $connection->execute("CALL Student_D_JOIN_StudentExam_studentList($localExamId,$foreignStudentId)");
        if ($id === null) {
            $this->studentList = [];
            $this->studentListMapping = [];
            return;
        }
        if ($this->studentList !== null) {
            foreach ($this->studentList as $index => $entity) {
                if ($id === $entity->getId()) {
                    array_splice($this->studentList, $index, 1);
                    break;
                }
            }
        }
        if ($this->studentListMapping !== null) {
            foreach ($this->studentListMapping as $index => $mapping) {
                if ($mapping->getStudentId() === $id) {
                    array_splice($this->studentListMapping, $index, 1);
                    break;
                }
            }
        }
    }

    /**
     * @param Exam $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(Exam $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}