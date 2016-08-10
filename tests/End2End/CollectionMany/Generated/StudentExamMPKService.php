<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class StudentExamMPKService
{

    /**
     * @var StudentExamMPKService
     */
    protected static $instance;

    /**
     * 
     * @return StudentExamMPKService
     */
    public static function getInstance() : StudentExamMPKService
    {
        if (self::$instance === null) {
            self::$instance = new StudentExamMPKService();
        }
        return self::$instance;
    }

    /**
     * 
     * @return StudentExamMPK
     */
    public function newInstance() : StudentExamMPK
    {
        return new StudentExamMPK();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return StudentExamMPK
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : StudentExamMPK
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return StudentExamMPK[]
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
     * @param StudentExamMPK[] $entityList
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