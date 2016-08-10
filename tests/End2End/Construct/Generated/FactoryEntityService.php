<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Construct\Generated;

use SiestaTest\End2End\Construct\Entity\EntityFactory;
use SiestaTest\End2End\Construct\Entity\Factory;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class FactoryEntityService
{

    /**
     * @var FactoryEntityService
     */
    protected static $instance;

    /**
     * 
     * @return FactoryEntityService
     */
    public static function getInstance() : FactoryEntityService
    {
        if (self::$instance === null) {
            self::$instance = new FactoryEntityService();
        }
        return self::$instance;
    }

    /**
     * @param int $id1
     * @param string $connectionName
     * 
     * @return Factory|null
     */
    public function getEntityByPrimaryKey(int $id1 = null, string $connectionName = null)
    {
        if ($id1 === null) {
            return null;
        }
        $id1 = Escaper::quoteInt($id1);
        $entityList = $this->executeStoredProcedure("CALL FactoryEntity_SB_PK($id1)", $connectionName);
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
        $connection->execute("CALL FactoryEntity_DB_PK($id1)");
    }

    /**
     * 
     * @return Factory
     */
    public function newInstance() : Factory
    {
        return EntityFactory::newFactoryEntity();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return Factory
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : Factory
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return Factory[]
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
     * @param Factory[] $entityList
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