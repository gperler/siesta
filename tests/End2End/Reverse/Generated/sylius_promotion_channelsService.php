<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_promotion_channelsService
{

    /**
     * @var sylius_promotion_channelsService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_promotion_channelsService
     */
    public static function getInstance() : sylius_promotion_channelsService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_promotion_channelsService();
        }
        return self::$instance;
    }

    /**
     * @param int $promotion_id
     * @param int $channel_id
     * @param string $connectionName
     * 
     * @return sylius_promotion_channels|null
     */
    public function getEntityByPrimaryKey(int $promotion_id = null, int $channel_id = null, string $connectionName = null)
    {
        if ($promotion_id === null || $channel_id === null) {
            return null;
        }
        $promotion_id = Escaper::quoteInt($promotion_id);
        $channel_id = Escaper::quoteInt($channel_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_promotion_channels_SB_PK($promotion_id,$channel_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $promotion_id
     * @param int $channel_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $promotion_id, int $channel_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $promotion_id = Escaper::quoteInt($promotion_id);
        $channel_id = Escaper::quoteInt($channel_id);
        $connection->execute("CALL sylius_promotion_channels_DB_PK($promotion_id,$channel_id)");
    }

    /**
     * 
     * @return sylius_promotion_channels
     */
    public function newInstance() : sylius_promotion_channels
    {
        return new sylius_promotion_channels();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_promotion_channels
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_promotion_channels
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_promotion_channels[]
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
     * @param sylius_promotion_channels[] $entityList
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