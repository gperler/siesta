<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class StudentExamService
{

    /**
     * @var StudentExamService
     */
    protected static $instance;

    /**
     * 
     * @return StudentExamService
     */
    public static function getInstance() : StudentExamService
    {
        if (self::$instance === null) {
            self::$instance = new StudentExamService();
        }
        return self::$instance;
    }

    /**
     * @param int $studentId
     * @param int $examId
     * @param string $connectionName
     * 
     * @return StudentExam|null
     */
    public function getEntityByPrimaryKey(int $studentId = null, int $examId = null, string $connectionName = null)
    {
        if ($studentId === null || $examId === null) {
            return null;
        }
        $studentId = Escaper::quoteInt($studentId);
        $examId = Escaper::quoteInt($examId);
        $entityList = $this->executeStoredProcedure("CALL StudentExam_SB_PK($studentId,$examId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $studentId
     * @param int $examId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $studentId, int $examId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $studentId = Escaper::quoteInt($studentId);
        $examId = Escaper::quoteInt($examId);
        $connection->execute("CALL StudentExam_DB_PK($studentId,$examId)");
    }

    /**
     * 
     * @return StudentExam
     */
    public function newInstance() : StudentExam
    {
        return new StudentExam();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return StudentExam
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : StudentExam
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return StudentExam[]
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
     * @param StudentExam[] $entityList
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