<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_channel_shipping_methodsService
{

    /**
     * @var sylius_channel_shipping_methodsService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_channel_shipping_methodsService
     */
    public static function getInstance() : sylius_channel_shipping_methodsService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_channel_shipping_methodsService();
        }
        return self::$instance;
    }

    /**
     * @param int $channel_id
     * @param int $shipping_method_id
     * @param string $connectionName
     * 
     * @return sylius_channel_shipping_methods|null
     */
    public function getEntityByPrimaryKey(int $channel_id = null, int $shipping_method_id = null, string $connectionName = null)
    {
        if ($channel_id === null || $shipping_method_id === null) {
            return null;
        }
        $channel_id = Escaper::quoteInt($channel_id);
        $shipping_method_id = Escaper::quoteInt($shipping_method_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_shipping_methods_SB_PK($channel_id,$shipping_method_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channel_id
     * @param int $shipping_method_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channel_id, int $shipping_method_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channel_id = Escaper::quoteInt($channel_id);
        $shipping_method_id = Escaper::quoteInt($shipping_method_id);
        $connection->execute("CALL sylius_channel_shipping_methods_DB_PK($channel_id,$shipping_method_id)");
    }

    /**
     * 
     * @return sylius_channel_shipping_methods
     */
    public function newInstance() : sylius_channel_shipping_methods
    {
        return new sylius_channel_shipping_methods();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_channel_shipping_methods
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_channel_shipping_methods
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_channel_shipping_methods[]
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
     * @param sylius_channel_shipping_methods[] $entityList
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