<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_promotion_order_itemService
{

    /**
     * @var sylius_promotion_order_itemService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_promotion_order_itemService
     */
    public static function getInstance() : sylius_promotion_order_itemService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_promotion_order_itemService();
        }
        return self::$instance;
    }

    /**
     * @param int $order_item_id
     * @param int $promotion_id
     * @param string $connectionName
     * 
     * @return sylius_promotion_order_item|null
     */
    public function getEntityByPrimaryKey(int $order_item_id = null, int $promotion_id = null, string $connectionName = null)
    {
        if ($order_item_id === null || $promotion_id === null) {
            return null;
        }
        $order_item_id = Escaper::quoteInt($order_item_id);
        $promotion_id = Escaper::quoteInt($promotion_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_order_item_SB_PK($order_item_id,$promotion_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $order_item_id
     * @param int $promotion_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $order_item_id, int $promotion_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $order_item_id = Escaper::quoteInt($order_item_id);
        $promotion_id = Escaper::quoteInt($promotion_id);
        $connection->execute("CALL sylius_promotion_order_item_DB_PK($order_item_id,$promotion_id)");
    }

    /**
     * 
     * @return sylius_promotion_order_item
     */
    public function newInstance() : sylius_promotion_order_item
    {
        return new sylius_promotion_order_item();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_promotion_order_item
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_promotion_order_item
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_promotion_order_item[]
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
     * @param sylius_promotion_order_item[] $entityList
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