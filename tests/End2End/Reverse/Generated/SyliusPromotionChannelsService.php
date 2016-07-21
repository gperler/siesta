<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusPromotionChannelsService
{

    /**
     * @var SyliusPromotionChannelsService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusPromotionChannelsService
     */
    public static function getInstance() : SyliusPromotionChannelsService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusPromotionChannelsService();
        }
        return self::$instance;
    }

    /**
     * @param int $promotionId
     * @param int $channelId
     * @param string $connectionName
     * 
     * @return SyliusPromotionChannels|null
     */
    public function getEntityByPrimaryKey(int $promotionId = null, int $channelId = null, string $connectionName = null)
    {
        if ($promotionId === null || $channelId === null) {
            return null;
        }
        $promotionId = Escaper::quoteInt($promotionId);
        $channelId = Escaper::quoteInt($channelId);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_channels_SB_PK($promotionId,$channelId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $promotionId
     * @param int $channelId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $promotionId, int $channelId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $promotionId = Escaper::quoteInt($promotionId);
        $channelId = Escaper::quoteInt($channelId);
        $connection->execute("CALL sylius_promotion_channels_DB_PK($promotionId,$channelId)");
    }

    /**
     * 
     * @return SyliusPromotionChannels
     */
    public function newInstance() : SyliusPromotionChannels
    {
        return new SyliusPromotionChannels();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusPromotionChannels
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusPromotionChannels
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusPromotionChannels[]
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
     * @param SyliusPromotionChannels[] $entityList
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