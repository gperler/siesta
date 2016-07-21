<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusChannelTaxonomyService
{

    /**
     * @var SyliusChannelTaxonomyService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusChannelTaxonomyService
     */
    public static function getInstance() : SyliusChannelTaxonomyService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusChannelTaxonomyService();
        }
        return self::$instance;
    }

    /**
     * @param int $channelId
     * @param int $taxonomyId
     * @param string $connectionName
     * 
     * @return SyliusChannelTaxonomy|null
     */
    public function getEntityByPrimaryKey(int $channelId = null, int $taxonomyId = null, string $connectionName = null)
    {
        if ($channelId === null || $taxonomyId === null) {
            return null;
        }
        $channelId = Escaper::quoteInt($channelId);
        $taxonomyId = Escaper::quoteInt($taxonomyId);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_taxonomy_SB_PK($channelId,$taxonomyId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channelId
     * @param int $taxonomyId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channelId, int $taxonomyId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channelId = Escaper::quoteInt($channelId);
        $taxonomyId = Escaper::quoteInt($taxonomyId);
        $connection->execute("CALL sylius_channel_taxonomy_DB_PK($channelId,$taxonomyId)");
    }

    /**
     * 
     * @return SyliusChannelTaxonomy
     */
    public function newInstance() : SyliusChannelTaxonomy
    {
        return new SyliusChannelTaxonomy();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusChannelTaxonomy
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusChannelTaxonomy
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusChannelTaxonomy[]
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
     * @param SyliusChannelTaxonomy[] $entityList
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