<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusProductChannelsService
{

    /**
     * @var SyliusProductChannelsService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusProductChannelsService
     */
    public static function getInstance() : SyliusProductChannelsService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusProductChannelsService();
        }
        return self::$instance;
    }

    /**
     * @param int $productId
     * @param int $channelId
     * @param string $connectionName
     * 
     * @return SyliusProductChannels|null
     */
    public function getEntityByPrimaryKey(int $productId = null, int $channelId = null, string $connectionName = null)
    {
        if ($productId === null || $channelId === null) {
            return null;
        }
        $productId = Escaper::quoteInt($productId);
        $channelId = Escaper::quoteInt($channelId);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_channels_SB_PK($productId,$channelId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $productId
     * @param int $channelId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $productId, int $channelId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $productId = Escaper::quoteInt($productId);
        $channelId = Escaper::quoteInt($channelId);
        $connection->execute("CALL sylius_product_channels_DB_PK($productId,$channelId)");
    }

    /**
     * 
     * @return SyliusProductChannels
     */
    public function newInstance() : SyliusProductChannels
    {
        return new SyliusProductChannels();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusProductChannels
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusProductChannels
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusProductChannels[]
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
     * @param SyliusProductChannels[] $entityList
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