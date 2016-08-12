<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Collection\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class CartUUIDService
{

    /**
     * @var CartUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return CartUUIDService
     */
    public static function getInstance() : CartUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new CartUUIDService();
        }
        return self::$instance;
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return CartUUID|null
     */
    public function getEntityByPrimaryKey(string $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $entityList = $this->executeStoredProcedure("CALL CartUUID_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL CartUUID_DB_PK($id)");
    }

    /**
     * 
     * @return CartUUID
     */
    public function newInstance() : CartUUID
    {
        return new CartUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return CartUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : CartUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return CartUUID[]
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
     * @param CartUUID[] $entityList
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