<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CustomServiceClass\Generated;

use SiestaTest\End2End\CustomServiceClass\ServiceClass\ServiceChild;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ServiceEntityService
{

    /**
     * @var ServiceChild
     */
    protected static $instance;

    /**
     * 
     * @return ServiceChild
     */
    public static function getInstance() : ServiceChild
    {
        if (self::$instance === null) {
            self::$instance = new ServiceChild();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return ServiceEntity|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL ServiceEntity_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL ServiceEntity_DB_PK($id)");
    }

    /**
     * 
     * @return ServiceEntity
     */
    public function newInstance() : ServiceEntity
    {
        return new ServiceEntity();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ServiceEntity
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ServiceEntity
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ServiceEntity[]
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
     * @param ServiceEntity[] $entityList
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