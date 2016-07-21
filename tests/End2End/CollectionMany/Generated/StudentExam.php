<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;

class StudentExam implements ArraySerializable
{

    const TABLE_NAME = "StudentExam";

    const COLUMN_STUDENTID = "FK_Student";

    const COLUMN_EXAMID = "FK_Exam";

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
    protected $studentId;

    /**
     * @var int
     */
    protected $examId;

    /**
     * @var Student
     */
    protected $StudentReference;

    /**
     * @var Exam
     */
    protected $ExamReference;

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
        $spCall = ($this->_existing) ? "CALL StudentExam_U(" : "CALL StudentExam_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        return $spCall . Escaper::quoteInt($this->studentId) . ',' . Escaper::quoteInt($this->examId) . ');';
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
        if ($cascade && $this->StudentReference !== null) {
            $this->StudentReference->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->ExamReference !== null) {
            $this->ExamReference->save($cascade, $cycleDetector, $connectionName);
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
        $this->studentId = $resultSet->getIntegerValue("FK_Student");
        $this->examId = $resultSet->getIntegerValue("FK_Exam");
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
        $connection->execute("CALL StudentExam_DB_PK()");
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
        $this->setStudentId($arrayAccessor->getIntegerValue("studentId"));
        $this->setExamId($arrayAccessor->getIntegerValue("examId"));
        $StudentReferenceArray = $arrayAccessor->getArray("StudentReference");
        if ($StudentReferenceArray !== null) {
            $StudentReference = StudentService::getInstance()->newInstance();
            $StudentReference->fromArray($StudentReferenceArray);
            $this->setStudentReference($StudentReference);
        }
        $ExamReferenceArray = $arrayAccessor->getArray("ExamReference");
        if ($ExamReferenceArray !== null) {
            $ExamReference = ExamService::getInstance()->newInstance();
            $ExamReference->fromArray($ExamReferenceArray);
            $this->setExamReference($ExamReference);
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
            "studentId" => $this->getStudentId(),
            "examId" => $this->getExamId()
        ];
        if ($this->StudentReference !== null) {
            $result["StudentReference"] = $this->StudentReference->toArray($cycleDetector);
        }
        if ($this->ExamReference !== null) {
            $result["ExamReference"] = $this->ExamReference->toArray($cycleDetector);
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
     * 
     * @return int|null
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * @param int $studentId
     * 
     * @return void
     */
    public function setStudentId(int $studentId = null)
    {
        $this->studentId = $studentId;
    }

    /**
     * 
     * @return int|null
     */
    public function getExamId()
    {
        return $this->examId;
    }

    /**
     * @param int $examId
     * 
     * @return void
     */
    public function setExamId(int $examId = null)
    {
        $this->examId = $examId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return Student|null
     */
    public function getStudentReference(bool $forceReload = false)
    {
        if ($this->StudentReference === null || $forceReload) {
            $this->StudentReference = StudentService::getInstance()->getEntityByPrimaryKey($this->studentId);
        }
        return $this->StudentReference;
    }

    /**
     * @param Student $entity
     * 
     * @return void
     */
    public function setStudentReference(Student $entity = null)
    {
        $this->StudentReference = $entity;
        $this->studentId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return Exam|null
     */
    public function getExamReference(bool $forceReload = false)
    {
        if ($this->ExamReference === null || $forceReload) {
            $this->ExamReference = ExamService::getInstance()->getEntityByPrimaryKey($this->examId);
        }
        return $this->ExamReference;
    }

    /**
     * @param Exam $entity
     * 
     * @return void
     */
    public function setExamReference(Exam $entity = null)
    {
        $this->ExamReference = $entity;
        $this->examId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param StudentExam $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(StudentExam $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return false;
    }

}