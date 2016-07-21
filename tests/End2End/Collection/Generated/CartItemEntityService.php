<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Collection\Generated;

use SiestaTest\End2End\Collection\Entity\CartItem;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class CartItemEntityService
{

    /**
     * @var CartItemEntityService
     */
    protected static $instance;

    /**
     * 
     * @return CartItemEntityService
     */
    public static function getInstance() : CartItemEntityService
    {
        if (self::$instance === null) {
            self::$instance = new CartItemEntityService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return CartItem|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL CartItem_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL CartItem_DB_PK($id)");
    }

    /**
     * @param int $cartId
     * @param string $connectionName
     * 
     * @return void
     */
    public function getEntityByCartReference(int $cartId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $cartId = Escaper::quoteInt($cartId);
        return $this->executeStoredProcedure("CALL CartItem_SB_R_cart ($cartId)", $connectionName);
    }

    /**
     * @param int $cartId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByCartReference(int $cartId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $cartId = Escaper::quoteInt($cartId);
        $connection->execute("CALL CartItem_DB_R_cart($cartId)");
    }

    /**
     * 
     * @return CartItem
     */
    public function newInstance() : CartItem
    {
        return new CartItem();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return CartItem
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : CartItem
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return CartItem[]
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
     * @param CartItem[] $entityList
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