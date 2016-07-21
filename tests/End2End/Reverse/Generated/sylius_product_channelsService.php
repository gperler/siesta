<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_product_channelsService
{

    /**
     * @var sylius_product_channelsService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_product_channelsService
     */
    public static function getInstance() : sylius_product_channelsService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_product_channelsService();
        }
        return self::$instance;
    }

    /**
     * @param int $product_id
     * @param int $channel_id
     * @param string $connectionName
     * 
     * @return sylius_product_channels|null
     */
    public function getEntityByPrimaryKey(int $product_id = null, int $channel_id = null, string $connectionName = null)
    {
        if ($product_id === null || $channel_id === null) {
            return null;
        }
        $product_id = Escaper::quoteInt($product_id);
        $channel_id = Escaper::quoteInt($channel_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_channels_SB_PK($product_id,$channel_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $product_id
     * @param int $channel_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $product_id, int $channel_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $product_id = Escaper::quoteInt($product_id);
        $channel_id = Escaper::quoteInt($channel_id);
        $connection->execute("CALL sylius_product_channels_DB_PK($product_id,$channel_id)");
    }

    /**
     * 
     * @return sylius_product_channels
     */
    public function newInstance() : sylius_product_channels
    {
        return new sylius_product_channels();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_product_channels
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_product_channels
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_product_channels[]
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
     * @param sylius_product_channels[] $entityList
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