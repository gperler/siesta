<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_channel_taxonomyService
{

    /**
     * @var sylius_channel_taxonomyService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_channel_taxonomyService
     */
    public static function getInstance() : sylius_channel_taxonomyService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_channel_taxonomyService();
        }
        return self::$instance;
    }

    /**
     * @param int $channel_id
     * @param int $taxonomy_id
     * @param string $connectionName
     * 
     * @return sylius_channel_taxonomy|null
     */
    public function getEntityByPrimaryKey(int $channel_id = null, int $taxonomy_id = null, string $connectionName = null)
    {
        if ($channel_id === null || $taxonomy_id === null) {
            return null;
        }
        $channel_id = Escaper::quoteInt($channel_id);
        $taxonomy_id = Escaper::quoteInt($taxonomy_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_taxonomy_SB_PK($channel_id,$taxonomy_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channel_id
     * @param int $taxonomy_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channel_id, int $taxonomy_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channel_id = Escaper::quoteInt($channel_id);
        $taxonomy_id = Escaper::quoteInt($taxonomy_id);
        $connection->execute("CALL sylius_channel_taxonomy_DB_PK($channel_id,$taxonomy_id)");
    }

    /**
     * 
     * @return sylius_channel_taxonomy
     */
    public function newInstance() : sylius_channel_taxonomy
    {
        return new sylius_channel_taxonomy();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_channel_taxonomy
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_channel_taxonomy
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_channel_taxonomy[]
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
     * @param sylius_channel_taxonomy[] $entityList
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