<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusPromotionOrderItemService
{

    /**
     * @var SyliusPromotionOrderItemService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusPromotionOrderItemService
     */
    public static function getInstance() : SyliusPromotionOrderItemService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusPromotionOrderItemService();
        }
        return self::$instance;
    }

    /**
     * @param int $orderItemId
     * @param int $promotionId
     * @param string $connectionName
     * 
     * @return SyliusPromotionOrderItem|null
     */
    public function getEntityByPrimaryKey(int $orderItemId = null, int $promotionId = null, string $connectionName = null)
    {
        if ($orderItemId === null || $promotionId === null) {
            return null;
        }
        $orderItemId = Escaper::quoteInt($orderItemId);
        $promotionId = Escaper::quoteInt($promotionId);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_order_item_SB_PK($orderItemId,$promotionId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $orderItemId
     * @param int $promotionId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $orderItemId, int $promotionId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $orderItemId = Escaper::quoteInt($orderItemId);
        $promotionId = Escaper::quoteInt($promotionId);
        $connection->execute("CALL sylius_promotion_order_item_DB_PK($orderItemId,$promotionId)");
    }

    /**
     * 
     * @return SyliusPromotionOrderItem
     */
    public function newInstance() : SyliusPromotionOrderItem
    {
        return new SyliusPromotionOrderItem();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusPromotionOrderItem
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusPromotionOrderItem
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusPromotionOrderItem[]
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
     * @param SyliusPromotionOrderItem[] $entityList
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