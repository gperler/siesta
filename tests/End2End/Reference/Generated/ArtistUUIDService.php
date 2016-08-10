<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reference\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ArtistUUIDService
{

    /**
     * @var ArtistUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return ArtistUUIDService
     */
    public static function getInstance() : ArtistUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new ArtistUUIDService();
        }
        return self::$instance;
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return ArtistUUID|null
     */
    public function getEntityByPrimaryKey(string $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $entityList = $this->executeStoredProcedure("CALL ArtistUUID_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL ArtistUUID_DB_PK($id)");
    }

    /**
     * 
     * @return ArtistUUID
     */
    public function newInstance() : ArtistUUID
    {
        return new ArtistUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ArtistUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ArtistUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ArtistUUID[]
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
     * @param ArtistUUID[] $entityList
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