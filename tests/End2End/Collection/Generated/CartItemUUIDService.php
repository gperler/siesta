<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Collection\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class CartItemUUIDService
{

    /**
     * @var CartItemUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return CartItemUUIDService
     */
    public static function getInstance() : CartItemUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new CartItemUUIDService();
        }
        return self::$instance;
    }

    /**
     * @param string $id
     * @param string $connectionName
     * 
     * @return CartItemUUID|null
     */
    public function getEntityByPrimaryKey(string $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteString($connection, $id);
        $entityList = $this->executeStoredProcedure("CALL CartItemUUID_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL CartItemUUID_DB_PK($id)");
    }

    /**
     * @param string $cartId
     * @param string $connectionName
     * 
     * @return CartUUID[]
     */
    public function getEntityByCartReference(string $cartId, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $cartId = Escaper::quoteString($connection, $cartId);
        return $this->executeStoredProcedure("CALL CartItemUUID_SB_R_cart ($cartId)", $connectionName);
    }

    /**
     * @param string $cartId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByCartReference(string $cartId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $cartId = Escaper::quoteString($connection, $cartId);
        $connection->execute("CALL CartItemUUID_DB_R_cart($cartId)");
    }

    /**
     * 
     * @return CartItemUUID
     */
    public function newInstance() : CartItemUUID
    {
        return new CartItemUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return CartItemUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : CartItemUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return CartItemUUID[]
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
     * @param CartItemUUID[] $entityList
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