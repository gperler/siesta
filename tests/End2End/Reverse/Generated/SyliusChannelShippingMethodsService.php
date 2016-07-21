<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusChannelShippingMethodsService
{

    /**
     * @var SyliusChannelShippingMethodsService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusChannelShippingMethodsService
     */
    public static function getInstance() : SyliusChannelShippingMethodsService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusChannelShippingMethodsService();
        }
        return self::$instance;
    }

    /**
     * @param int $channelId
     * @param int $shippingMethodId
     * @param string $connectionName
     * 
     * @return SyliusChannelShippingMethods|null
     */
    public function getEntityByPrimaryKey(int $channelId = null, int $shippingMethodId = null, string $connectionName = null)
    {
        if ($channelId === null || $shippingMethodId === null) {
            return null;
        }
        $channelId = Escaper::quoteInt($channelId);
        $shippingMethodId = Escaper::quoteInt($shippingMethodId);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_shipping_methods_SB_PK($channelId,$shippingMethodId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channelId
     * @param int $shippingMethodId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channelId, int $shippingMethodId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channelId = Escaper::quoteInt($channelId);
        $shippingMethodId = Escaper::quoteInt($shippingMethodId);
        $connection->execute("CALL sylius_channel_shipping_methods_DB_PK($channelId,$shippingMethodId)");
    }

    /**
     * 
     * @return SyliusChannelShippingMethods
     */
    public function newInstance() : SyliusChannelShippingMethods
    {
        return new SyliusChannelShippingMethods();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusChannelShippingMethods
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusChannelShippingMethods
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusChannelShippingMethods[]
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
     * @param SyliusChannelShippingMethods[] $entityList
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