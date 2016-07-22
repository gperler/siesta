<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class StudentUUIDService
{

    /**
     * @var StudentUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return StudentUUIDService
     */
    public static function getInstance() : StudentUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new StudentUUIDService();
        }
        return self::$instance;
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return StudentUUID|null
     */
    public function getEntityByPrimaryKey(string $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $entityList = $this->executeStoredProcedure("CALL StudentUUID_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL StudentUUID_DB_PK($id)");
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return StudentUUID[]
     */
    public function getStudentUUIDJoinStudentExamUUID(string $id, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        return $this->executeStoredProcedure("CALL StudentUUID_S_JOIN_StudentExamUUID_studentList($id)", $connectionName);
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteStudentUUIDJoinStudentExamUUID(string $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $connection->execute("CALL StudentUUID_D_JOIN_StudentExamUUID_studentList($id)");
    }

    /**
     * 
     * @return StudentUUID
     */
    public function newInstance() : StudentUUID
    {
        return new StudentUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return StudentUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : StudentUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return StudentUUID[]
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
     * @param StudentUUID[] $entityList
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