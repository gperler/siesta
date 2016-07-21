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

class StudentExamMPK implements ArraySerializable
{

    const TABLE_NAME = "StudentExamMPK";

    const COLUMN_STUDENTID1 = "FK_Student1";

    const COLUMN_STUDENTID2 = "FK_Student2";

    const COLUMN_EXAMID1 = "FK_Exam1";

    const COLUMN_EXAMID2 = "FK_Exam2";

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
    protected $studentId1;

    /**
     * @var string
     */
    protected $studentId2;

    /**
     * @var string
     */
    protected $examId1;

    /**
     * @var string
     */
    protected $examId2;

    /**
     * @var StudentMPK
     */
    protected $StudentReference;

    /**
     * @var ExamMPK
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
        $spCall = ($this->_existing) ? "CALL StudentExamMPK_U(" : "CALL StudentExamMPK_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        return $spCall . Escaper::quoteString($connection, $this->studentId1) . ',' . Escaper::quoteString($connection, $this->studentId2) . ',' . Escaper::quoteString($connection, $this->examId1) . ',' . Escaper::quoteString($connection, $this->examId2) . ');';
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
        $this->studentId1 = $resultSet->getStringValue("FK_Student1");
        $this->studentId2 = $resultSet->getStringValue("FK_Student2");
        $this->examId1 = $resultSet->getStringValue("FK_Exam1");
        $this->examId2 = $resultSet->getStringValue("FK_Exam2");
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
        $connection->execute("CALL StudentExamMPK_DB_PK()");
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
        $this->setStudentId1($arrayAccessor->getStringValue("studentId1"));
        $this->setStudentId2($arrayAccessor->getStringValue("studentId2"));
        $this->setExamId1($arrayAccessor->getStringValue("examId1"));
        $this->setExamId2($arrayAccessor->getStringValue("examId2"));
        $StudentReferenceArray = $arrayAccessor->getArray("StudentReference");
        if ($StudentReferenceArray !== null) {
            $StudentReference = StudentMPKService::getInstance()->newInstance();
            $StudentReference->fromArray($StudentReferenceArray);
            $this->setStudentReference($StudentReference);
        }
        $ExamReferenceArray = $arrayAccessor->getArray("ExamReference");
        if ($ExamReferenceArray !== null) {
            $ExamReference = ExamMPKService::getInstance()->newInstance();
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
            "studentId1" => $this->getStudentId1(),
            "studentId2" => $this->getStudentId2(),
            "examId1" => $this->getExamId1(),
            "examId2" => $this->getExamId2()
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
    public function getStudentId1()
    {
        return $this->studentId1;
    }

    /**
     * @param string $studentId1
     * 
     * @return void
     */
    public function setStudentId1(string $studentId1 = null)
    {
        $this->studentId1 = StringUtil::trimToNull($studentId1, 36);
    }

    /**
     * 
     * @return string|null
     */
    public function getStudentId2()
    {
        return $this->studentId2;
    }

    /**
     * @param string $studentId2
     * 
     * @return void
     */
    public function setStudentId2(string $studentId2 = null)
    {
        $this->studentId2 = StringUtil::trimToNull($studentId2, 36);
    }

    /**
     * 
     * @return string|null
     */
    public function getExamId1()
    {
        return $this->examId1;
    }

    /**
     * @param string $examId1
     * 
     * @return void
     */
    public function setExamId1(string $examId1 = null)
    {
        $this->examId1 = StringUtil::trimToNull($examId1, 36);
    }

    /**
     * 
     * @return string|null
     */
    public function getExamId2()
    {
        return $this->examId2;
    }

    /**
     * @param string $examId2
     * 
     * @return void
     */
    public function setExamId2(string $examId2 = null)
    {
        $this->examId2 = StringUtil::trimToNull($examId2, 36);
    }

    /**
     * @param bool $forceReload
     * 
     * @return StudentMPK|null
     */
    public function getStudentReference(bool $forceReload = false)
    {
        if ($this->StudentReference === null || $forceReload) {
            $this->StudentReference = StudentMPKService::getInstance()->getEntityByPrimaryKey($this->studentId1, $this->studentId2);
        }
        return $this->StudentReference;
    }

    /**
     * @param StudentMPK $entity
     * 
     * @return void
     */
    public function setStudentReference(StudentMPK $entity = null)
    {
        $this->StudentReference = $entity;
        $this->studentId1 = ($entity !== null) ? $entity->getId1(true) : null;
        $this->studentId2 = ($entity !== null) ? $entity->getId2(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return ExamMPK|null
     */
    public function getExamReference(bool $forceReload = false)
    {
        if ($this->ExamReference === null || $forceReload) {
            $this->ExamReference = ExamMPKService::getInstance()->getEntityByPrimaryKey($this->examId1, $this->examId2);
        }
        return $this->ExamReference;
    }

    /**
     * @param ExamMPK $entity
     * 
     * @return void
     */
    public function setExamReference(ExamMPK $entity = null)
    {
        $this->ExamReference = $entity;
        $this->examId1 = ($entity !== null) ? $entity->getId1(true) : null;
        $this->examId2 = ($entity !== null) ? $entity->getId2(true) : null;
    }

    /**
     * @param StudentExamMPK $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(StudentExamMPK $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return false;
    }

}