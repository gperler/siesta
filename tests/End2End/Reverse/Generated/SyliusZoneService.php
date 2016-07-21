<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusZoneService
{

    /**
     * @var SyliusZoneService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusZoneService
     */
    public static function getInstance() : SyliusZoneService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusZoneService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return SyliusZone|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL sylius_zone_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL sylius_zone_DB_PK($id)");
    }

    /**
     * 
     * @return SyliusZone
     */
    public function newInstance() : SyliusZone
    {
        return new SyliusZone();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusZone
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusZone
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusZone[]
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
     * @param SyliusZone[] $entityList
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