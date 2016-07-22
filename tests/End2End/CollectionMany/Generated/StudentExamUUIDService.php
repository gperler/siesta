<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class StudentExamUUIDService
{

    /**
     * @var StudentExamUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return StudentExamUUIDService
     */
    public static function getInstance() : StudentExamUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new StudentExamUUIDService();
        }
        return self::$instance;
    }

    /**
     * 
     * @return StudentExamUUID
     */
    public function newInstance() : StudentExamUUID
    {
        return new StudentExamUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return StudentExamUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : StudentExamUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return StudentExamUUID[]
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
     * @param StudentExamUUID[] $entityList
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