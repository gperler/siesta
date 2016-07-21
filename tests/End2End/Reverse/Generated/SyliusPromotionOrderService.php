<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusPromotionOrderService
{

    /**
     * @var SyliusPromotionOrderService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusPromotionOrderService
     */
    public static function getInstance() : SyliusPromotionOrderService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusPromotionOrderService();
        }
        return self::$instance;
    }

    /**
     * @param int $orderId
     * @param int $promotionId
     * @param string $connectionName
     * 
     * @return SyliusPromotionOrder|null
     */
    public function getEntityByPrimaryKey(int $orderId = null, int $promotionId = null, string $connectionName = null)
    {
        if ($orderId === null || $promotionId === null) {
            return null;
        }
        $orderId = Escaper::quoteInt($orderId);
        $promotionId = Escaper::quoteInt($promotionId);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_order_SB_PK($orderId,$promotionId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $orderId
     * @param int $promotionId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $orderId, int $promotionId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $orderId = Escaper::quoteInt($orderId);
        $promotionId = Escaper::quoteInt($promotionId);
        $connection->execute("CALL sylius_promotion_order_DB_PK($orderId,$promotionId)");
    }

    /**
     * 
     * @return SyliusPromotionOrder
     */
    public function newInstance() : SyliusPromotionOrder
    {
        return new SyliusPromotionOrder();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusPromotionOrder
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusPromotionOrder
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusPromotionOrder[]
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
     * @param SyliusPromotionOrder[] $entityList
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