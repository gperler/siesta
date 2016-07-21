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