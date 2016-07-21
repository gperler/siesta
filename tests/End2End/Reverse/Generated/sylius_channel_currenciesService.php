<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_channel_currenciesService
{

    /**
     * @var sylius_channel_currenciesService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_channel_currenciesService
     */
    public static function getInstance() : sylius_channel_currenciesService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_channel_currenciesService();
        }
        return self::$instance;
    }

    /**
     * @param int $channel_id
     * @param int $currency_id
     * @param string $connectionName
     * 
     * @return sylius_channel_currencies|null
     */
    public function getEntityByPrimaryKey(int $channel_id = null, int $currency_id = null, string $connectionName = null)
    {
        if ($channel_id === null || $currency_id === null) {
            return null;
        }
        $channel_id = Escaper::quoteInt($channel_id);
        $currency_id = Escaper::quoteInt($currency_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_currencies_SB_PK($channel_id,$currency_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channel_id
     * @param int $currency_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channel_id, int $currency_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channel_id = Escaper::quoteInt($channel_id);
        $currency_id = Escaper::quoteInt($currency_id);
        $connection->execute("CALL sylius_channel_currencies_DB_PK($channel_id,$currency_id)");
    }

    /**
     * 
     * @return sylius_channel_currencies
     */
    public function newInstance() : sylius_channel_currencies
    {
        return new sylius_channel_currencies();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_channel_currencies
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_channel_currencies
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_channel_currencies[]
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
     * @param sylius_channel_currencies[] $entityList
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