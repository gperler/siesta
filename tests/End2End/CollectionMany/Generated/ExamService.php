<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ExamService
{

    /**
     * @var ExamService
     */
    protected static $instance;

    /**
     * 
     * @return ExamService
     */
    public static function getInstance() : ExamService
    {
        if (self::$instance === null) {
            self::$instance = new ExamService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return Exam|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL Exam_SB_PK($id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL Exam_DB_PK($id)");
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return Exam[]
     */
    public function getExamJoinStudentExam(int $id, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        return $this->executeStoredProcedure("CALL Exam_S_JOIN_StudentExam_examList($id)", $connectionName);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteExamJoinStudentExam(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL Exam_D_JOIN_StudentExam_examList($id)");
    }

    /**
     * 
     * @return Exam
     */
    public function newInstance() : Exam
    {
        return new Exam();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return Exam
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : Exam
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return Exam[]
     */
    public function executeStoredProcedure(string $spCall, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $entityList = [];
        $resultSet = $connection->executeStoredProcedure($spCall);
        while ($resultSet->hasNext()) {
            $entityList[] = $this->createInstanceFromResultSet($resultSet);
        }
        $resultSet->close();
        return $entityList;
    }

    /**
     * @param Exam[] $entityList
     * @param string $connectionName
     * 
     * @return void
     */
    public function batchSave(array $entityList, string $connectionName = null)
    {
        $batchCall = "";
        foreach ($entityList as $entity) {
            $batchCall .= $entity->createSaveStoredProcedureCall();
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $connection->execute($batchCall);
    }

}