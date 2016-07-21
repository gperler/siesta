<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ExamUUIDService
{

    /**
     * @var ExamUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return ExamUUIDService
     */
    public static function getInstance() : ExamUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new ExamUUIDService();
        }
        return self::$instance;
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return ExamUUID|null
     */
    public function getEntityByPrimaryKey(string $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $entityList = $this->executeStoredProcedure("CALL ExamUUID_SB_PK($id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $connection->execute("CALL ExamUUID_DB_PK($id)");
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return ExamUUID[]
     */
    public function getExamUUIDJoinStudentExamUUID(string $id, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        return $this->executeStoredProcedure("CALL ExamUUID_S_JOIN_StudentExamUUID_examList($id)", $connectionName);
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteExamUUIDJoinStudentExamUUID(string $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $connection->execute("CALL ExamUUID_D_JOIN_StudentExamUUID_examList($id)");
    }

    /**
     * 
     * @return ExamUUID
     */
    public function newInstance() : ExamUUID
    {
        return new ExamUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ExamUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ExamUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ExamUUID[]
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
     * @param ExamUUID[] $entityList
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