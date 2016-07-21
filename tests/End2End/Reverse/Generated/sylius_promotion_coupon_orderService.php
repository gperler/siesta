<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_promotion_coupon_orderService
{

    /**
     * @var sylius_promotion_coupon_orderService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_promotion_coupon_orderService
     */
    public static function getInstance() : sylius_promotion_coupon_orderService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_promotion_coupon_orderService();
        }
        return self::$instance;
    }

    /**
     * @param int $order_id
     * @param int $promotion_coupon_id
     * @param string $connectionName
     * 
     * @return sylius_promotion_coupon_order|null
     */
    public function getEntityByPrimaryKey(int $order_id = null, int $promotion_coupon_id = null, string $connectionName = null)
    {
        if ($order_id === null || $promotion_coupon_id === null) {
            return null;
        }
        $order_id = Escaper::quoteInt($order_id);
        $promotion_coupon_id = Escaper::quoteInt($promotion_coupon_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_coupon_order_SB_PK($order_id,$promotion_coupon_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $order_id
     * @param int $promotion_coupon_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $order_id, int $promotion_coupon_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $order_id = Escaper::quoteInt($order_id);
        $promotion_coupon_id = Escaper::quoteInt($promotion_coupon_id);
        $connection->execute("CALL sylius_promotion_coupon_order_DB_PK($order_id,$promotion_coupon_id)");
    }

    /**
     * 
     * @return sylius_promotion_coupon_order
     */
    public function newInstance() : sylius_promotion_coupon_order
    {
        return new sylius_promotion_coupon_order();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_promotion_coupon_order
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_promotion_coupon_order
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_promotion_coupon_order[]
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
     * @param sylius_promotion_coupon_order[] $entityList
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