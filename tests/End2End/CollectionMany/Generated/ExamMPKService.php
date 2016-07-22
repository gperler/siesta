<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ExamMPKService
{

    /**
     * @var ExamMPKService
     */
    protected static $instance;

    /**
     * 
     * @return ExamMPKService
     */
    public static function getInstance() : ExamMPKService
    {
        if (self::$instance === null) {
            self::$instance = new ExamMPKService();
        }
        return self::$instance;
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return ExamMPK|null
     */
    public function getEntityByPrimaryKey(string $id1 = null, string $id2 = null, string $connectionName = null)
    {
        if ($id1 === null || $id2 === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteString($connection, $id1);
        $id2 = Escaper::quoteString($connection, $id2);
        $entityList = $this->executeStoredProcedure("CALL ExamMPK_SB_PK($id1,$id2)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $id1, string $id2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteString($connection, $id1);
        $id2 = Escaper::quoteString($connection, $id2);
        $connection->execute("CALL ExamMPK_DB_PK($id1,$id2)");
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return ExamMPK[]
     */
    public function getExamMPKJoinStudentExamMPK(string $id1, string $id2, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteString($connection, $id1);
        $id2 = Escaper::quoteString($connection, $id2);
        return $this->executeStoredProcedure("CALL ExamMPK_S_JOIN_StudentExamMPK_examList($id1,$id2)", $connectionName);
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteExamMPKJoinStudentExamMPK(string $id1, string $id2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteString($connection, $id1);
        $id2 = Escaper::quoteString($connection, $id2);
        $connection->execute("CALL ExamMPK_D_JOIN_StudentExamMPK_examList($id1,$id2)");
    }

    /**
     * 
     * @return ExamMPK
     */
    public function newInstance() : ExamMPK
    {
        return new ExamMPK();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ExamMPK
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ExamMPK
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ExamMPK[]
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
     * @param ExamMPK[] $entityList
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