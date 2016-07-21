<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrTypeChildsService
{

    /**
     * @var PhpcrTypeChildsService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrTypeChildsService
     */
    public static function getInstance() : PhpcrTypeChildsService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrTypeChildsService();
        }
        return self::$instance;
    }

    /**
     * 
     * @return PhpcrTypeChilds
     */
    public function newInstance() : PhpcrTypeChilds
    {
        return new PhpcrTypeChilds();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrTypeChilds
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrTypeChilds
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrTypeChilds[]
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
     * @param PhpcrTypeChilds[] $entityList
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