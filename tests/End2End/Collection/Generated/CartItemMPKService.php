<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Collection\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class CartItemMPKService
{

    /**
     * @var CartItemMPKService
     */
    protected static $instance;

    /**
     * 
     * @return CartItemMPKService
     */
    public static function getInstance() : CartItemMPKService
    {
        if (self::$instance === null) {
            self::$instance = new CartItemMPKService();
        }
        return self::$instance;
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return CartItemMPK|null
     */
    public function getEntityByPrimaryKey(string $id1 = null, string $id2 = null, string $connectionName = null)
    {
        if ($id1 === null || $id2 === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteString($connection, $id1);
        $id2 = Escaper::quoteString($connection, $id2);
        $entityList = $this->executeStoredProcedure("CALL CartItemMPK_SB_PK($id1,$id2)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $id1
     * @param string $id2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $id1, string $id2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id1 = Escaper::quoteString($connection, $id1);
        $id2 = Escaper::quoteString($connection, $id2);
        $connection->execute("CALL CartItemMPK_DB_PK($id1,$id2)");
    }

    /**
     * @param string $cartId1
     * @param string $cartId2
     * @param string $connectionName
     * 
     * @return void
     */
    public function getEntityByCartReference(string $cartId1, string $cartId2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $cartId1 = Escaper::quoteString($connection, $cartId1);
        $cartId2 = Escaper::quoteString($connection, $cartId2);
        return $this->executeStoredProcedure("CALL CartItemMPK_SB_R_cart ($cartId1,$cartId2)", $connectionName);
    }

    /**
     * @param string $cartId1
     * @param string $cartId2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByCartReference(string $cartId1, string $cartId2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $cartId1 = Escaper::quoteString($connection, $cartId1);
        $cartId2 = Escaper::quoteString($connection, $cartId2);
        $connection->execute("CALL CartItemMPK_DB_R_cart($cartId1,$cartId2)");
    }

    /**
     * 
     * @return CartItemMPK
     */
    public function newInstance() : CartItemMPK
    {
        return new CartItemMPK();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return CartItemMPK
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : CartItemMPK
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return CartItemMPK[]
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
     * @param CartItemMPK[] $entityList
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