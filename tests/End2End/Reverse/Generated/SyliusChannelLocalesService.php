<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusChannelLocalesService
{

    /**
     * @var SyliusChannelLocalesService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusChannelLocalesService
     */
    public static function getInstance() : SyliusChannelLocalesService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusChannelLocalesService();
        }
        return self::$instance;
    }

    /**
     * @param int $channelId
     * @param int $localeId
     * @param string $connectionName
     * 
     * @return SyliusChannelLocales|null
     */
    public function getEntityByPrimaryKey(int $channelId = null, int $localeId = null, string $connectionName = null)
    {
        if ($channelId === null || $localeId === null) {
            return null;
        }
        $channelId = Escaper::quoteInt($channelId);
        $localeId = Escaper::quoteInt($localeId);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_locales_SB_PK($channelId,$localeId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channelId
     * @param int $localeId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channelId, int $localeId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channelId = Escaper::quoteInt($channelId);
        $localeId = Escaper::quoteInt($localeId);
        $connection->execute("CALL sylius_channel_locales_DB_PK($channelId,$localeId)");
    }

    /**
     * 
     * @return SyliusChannelLocales
     */
    public function newInstance() : SyliusChannelLocales
    {
        return new SyliusChannelLocales();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusChannelLocales
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusChannelLocales
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusChannelLocales[]
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
     * @param SyliusChannelLocales[] $entityList
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