<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CustomServiceClass\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ServiceReferenceService
{

    /**
     * @var ServiceReferenceService
     */
    protected static $instance;

    /**
     * 
     * @return ServiceReferenceService
     */
    public static function getInstance() : ServiceReferenceService
    {
        if (self::$instance === null) {
            self::$instance = new ServiceReferenceService();
        }
        return self::$instance;
    }

    /**
     * @param int $id2
     * @param string $connectionName
     * 
     * @return ServiceReference|null
     */
    public function getEntityByPrimaryKey(int $id2 = null, string $connectionName = null)
    {
        if ($id2 === null) {
            return null;
        }
        $id2 = Escaper::quoteInt($id2);
        $entityList = $this->executeStoredProcedure("CALL service_reference_SB_PK($id2)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $id2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $id2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id2 = Escaper::quoteInt($id2);
        $connection->execute("CALL service_reference_DB_PK($id2)");
    }

    /**
     * 
     * @return ServiceReference
     */
    public function newInstance() : ServiceReference
    {
        return new ServiceReference();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ServiceReference
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ServiceReference
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ServiceReference[]
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
     * @param ServiceReference[] $entityList
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