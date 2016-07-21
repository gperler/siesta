<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusChannelCurrenciesService
{

    /**
     * @var SyliusChannelCurrenciesService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusChannelCurrenciesService
     */
    public static function getInstance() : SyliusChannelCurrenciesService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusChannelCurrenciesService();
        }
        return self::$instance;
    }

    /**
     * @param int $channelId
     * @param int $currencyId
     * @param string $connectionName
     * 
     * @return SyliusChannelCurrencies|null
     */
    public function getEntityByPrimaryKey(int $channelId = null, int $currencyId = null, string $connectionName = null)
    {
        if ($channelId === null || $currencyId === null) {
            return null;
        }
        $channelId = Escaper::quoteInt($channelId);
        $currencyId = Escaper::quoteInt($currencyId);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_currencies_SB_PK($channelId,$currencyId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channelId
     * @param int $currencyId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channelId, int $currencyId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channelId = Escaper::quoteInt($channelId);
        $currencyId = Escaper::quoteInt($currencyId);
        $connection->execute("CALL sylius_channel_currencies_DB_PK($channelId,$currencyId)");
    }

    /**
     * 
     * @return SyliusChannelCurrencies
     */
    public function newInstance() : SyliusChannelCurrencies
    {
        return new SyliusChannelCurrencies();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusChannelCurrencies
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusChannelCurrencies
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusChannelCurrencies[]
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
     * @param SyliusChannelCurrencies[] $entityList
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