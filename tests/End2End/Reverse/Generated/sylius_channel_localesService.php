<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_channel_localesService
{

    /**
     * @var sylius_channel_localesService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_channel_localesService
     */
    public static function getInstance() : sylius_channel_localesService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_channel_localesService();
        }
        return self::$instance;
    }

    /**
     * @param int $channel_id
     * @param int $locale_id
     * @param string $connectionName
     * 
     * @return sylius_channel_locales|null
     */
    public function getEntityByPrimaryKey(int $channel_id = null, int $locale_id = null, string $connectionName = null)
    {
        if ($channel_id === null || $locale_id === null) {
            return null;
        }
        $channel_id = Escaper::quoteInt($channel_id);
        $locale_id = Escaper::quoteInt($locale_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_locales_SB_PK($channel_id,$locale_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channel_id
     * @param int $locale_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channel_id, int $locale_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channel_id = Escaper::quoteInt($channel_id);
        $locale_id = Escaper::quoteInt($locale_id);
        $connection->execute("CALL sylius_channel_locales_DB_PK($channel_id,$locale_id)");
    }

    /**
     * 
     * @return sylius_channel_locales
     */
    public function newInstance() : sylius_channel_locales
    {
        return new sylius_channel_locales();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_channel_locales
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_channel_locales
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_channel_locales[]
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
     * @param sylius_channel_locales[] $entityList
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