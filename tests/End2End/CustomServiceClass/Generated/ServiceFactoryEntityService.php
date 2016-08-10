<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CustomServiceClass\Generated;

use SiestaTest\End2End\CustomServiceClass\ServiceClass\ServiceFactory;
use SiestaTest\End2End\CustomServiceClass\ServiceClass\ServiceFactoryChild;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ServiceFactoryEntityService
{

    /**
     * @var ServiceFactoryChild
     */
    protected static $instance;

    /**
     * 
     * @return ServiceFactoryChild
     */
    public static function getInstance() : ServiceFactoryChild
    {
        if (self::$instance === null) {
            self::$instance = new ServiceFactoryChild();
        }
        return self::$instance;
    }

    /**
     * @param int $id1
     * @param string $connectionName
     * 
     * @return ServiceFactoryEntity|null
     */
    public function getEntityByPrimaryKey(int $id1 = null, string $connectionName = null)
    {
        if ($id1 === null) {
            return null;
        }
        $id1 = Escaper::quoteInt($id1);
        $entityList = $this->executeStoredProcedure("CALL ServiceFactoryEntity_SB_PK($id1)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $id1
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $id1, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteInt($id1);
        $connection->execute("CALL ServiceFactoryEntity_DB_PK($id1)");
    }

    /**
     * 
     * @return ServiceFactoryEntity
     */
    public function newInstance() : ServiceFactoryEntity
    {
        return new ServiceFactoryEntity();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ServiceFactoryEntity
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ServiceFactoryEntity
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ServiceFactoryEntity[]
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
     * @param ServiceFactoryEntity[] $entityList
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