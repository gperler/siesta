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
use Siesta\Util\StringUtil;

class StudentExamUUID implements ArraySerializable
{

    const TABLE_NAME = "StudentExamUUID";

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
     * @var string
     */
    protected $studentId;

    /**
     * @var string
     */
    protected $examId;

    /**
     * @var StudentUUID
     */
    protected $StudentReference;

    /**
     * @var ExamUUID
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
        $spCall = ($this->_existing) ? "CALL StudentExamUUID_U(" : "CALL StudentExamUUID_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        return $spCall . Escaper::quoteString($connection, $this->studentId) . ',' . Escaper::quoteString($connection, $this->examId) . ');';
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
        $this->studentId = $resultSet->getStringValue("FK_Student");
        $this->examId = $resultSet->getStringValue("FK_Exam");
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
        $connection->execute("CALL StudentExamUUID_DB_PK()");
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
        $this->setStudentId($arrayAccessor->getStringValue("studentId"));
        $this->setExamId($arrayAccessor->getStringValue("examId"));
        $StudentReferenceArray = $arrayAccessor->getArray("StudentReference");
        if ($StudentReferenceArray !== null) {
            $StudentReference = StudentUUIDService::getInstance()->newInstance();
            $StudentReference->fromArray($StudentReferenceArray);
            $this->setStudentReference($StudentReference);
        }
        $ExamReferenceArray = $arrayAccessor->getArray("ExamReference");
        if ($ExamReferenceArray !== null) {
            $ExamReference = ExamUUIDService::getInstance()->newInstance();
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
     * @return string|null
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * @param string $studentId
     * 
     * @return void
     */
    public function setStudentId(string $studentId = null)
    {
        $this->studentId = StringUtil::trimToNull($studentId, 36);
    }

    /**
     * 
     * @return string|null
     */
    public function getExamId()
    {
        return $this->examId;
    }

    /**
     * @param string $examId
     * 
     * @return void
     */
    public function setExamId(string $examId = null)
    {
        $this->examId = StringUtil::trimToNull($examId, 36);
    }

    /**
     * @param bool $forceReload
     * 
     * @return StudentUUID|null
     */
    public function getStudentReference(bool $forceReload = false)
    {
        if ($this->StudentReference === null || $forceReload) {
            $this->StudentReference = StudentUUIDService::getInstance()->getEntityByPrimaryKey($this->studentId);
        }
        return $this->StudentReference;
    }

    /**
     * @param StudentUUID $entity
     * 
     * @return void
     */
    public function setStudentReference(StudentUUID $entity = null)
    {
        $this->StudentReference = $entity;
        $this->studentId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return ExamUUID|null
     */
    public function getExamReference(bool $forceReload = false)
    {
        if ($this->ExamReference === null || $forceReload) {
            $this->ExamReference = ExamUUIDService::getInstance()->getEntityByPrimaryKey($this->examId);
        }
        return $this->ExamReference;
    }

    /**
     * @param ExamUUID $entity
     * 
     * @return void
     */
    public function setExamReference(ExamUUID $entity = null)
    {
        $this->ExamReference = $entity;
        $this->examId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param StudentExamUUID $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(StudentExamUUID $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return false;
    }

}