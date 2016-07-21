<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusPromotionCouponOrderService
{

    /**
     * @var SyliusPromotionCouponOrderService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusPromotionCouponOrderService
     */
    public static function getInstance() : SyliusPromotionCouponOrderService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusPromotionCouponOrderService();
        }
        return self::$instance;
    }

    /**
     * @param int $orderId
     * @param int $promotionCouponId
     * @param string $connectionName
     * 
     * @return SyliusPromotionCouponOrder|null
     */
    public function getEntityByPrimaryKey(int $orderId = null, int $promotionCouponId = null, string $connectionName = null)
    {
        if ($orderId === null || $promotionCouponId === null) {
            return null;
        }
        $orderId = Escaper::quoteInt($orderId);
        $promotionCouponId = Escaper::quoteInt($promotionCouponId);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_coupon_order_SB_PK($orderId,$promotionCouponId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $orderId
     * @param int $promotionCouponId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $orderId, int $promotionCouponId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $orderId = Escaper::quoteInt($orderId);
        $promotionCouponId = Escaper::quoteInt($promotionCouponId);
        $connection->execute("CALL sylius_promotion_coupon_order_DB_PK($orderId,$promotionCouponId)");
    }

    /**
     * 
     * @return SyliusPromotionCouponOrder
     */
    public function newInstance() : SyliusPromotionCouponOrder
    {
        return new SyliusPromotionCouponOrder();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusPromotionCouponOrder
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusPromotionCouponOrder
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusPromotionCouponOrder[]
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
     * @param SyliusPromotionCouponOrder[] $entityList
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