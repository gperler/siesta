<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class phpcr_nodes_weakreferencesService
{

    /**
     * @var phpcr_nodes_weakreferencesService
     */
    protected static $instance;

    /**
     * 
     * @return phpcr_nodes_weakreferencesService
     */
    public static function getInstance() : phpcr_nodes_weakreferencesService
    {
        if (self::$instance === null) {
            self::$instance = new phpcr_nodes_weakreferencesService();
        }
        return self::$instance;
    }

    /**
     * @param int $source_id
     * @param string $source_property_name
     * @param int $target_id
     * @param string $connectionName
     * 
     * @return phpcr_nodes_weakreferences|null
     */
    public function getEntityByPrimaryKey(int $source_id = null, string $source_property_name = null, int $target_id = null, string $connectionName = null)
    {
        if ($source_id === null || $source_property_name === null || $target_id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $source_id = Escaper::quoteInt($source_id);
        $source_property_name = Escaper::quoteString($connection, $source_property_name);
        $target_id = Escaper::quoteInt($target_id);
        $entityList = $this->executeStoredProcedure("CALL phpcr_nodes_weakreferences_SB_PK($source_id,$source_property_name,$target_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $source_id
     * @param string $source_property_name
     * @param int $target_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $source_id, string $source_property_name, int $target_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $source_id = Escaper::quoteInt($source_id);
        $source_property_name = Escaper::quoteString($connection, $source_property_name);
        $target_id = Escaper::quoteInt($target_id);
        $connection->execute("CALL phpcr_nodes_weakreferences_DB_PK($source_id,$source_property_name,$target_id)");
    }

    /**
     * 
     * @return phpcr_nodes_weakreferences
     */
    public function newInstance() : phpcr_nodes_weakreferences
    {
        return new phpcr_nodes_weakreferences();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return phpcr_nodes_weakreferences
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : phpcr_nodes_weakreferences
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return phpcr_nodes_weakreferences[]
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
     * @param phpcr_nodes_weakreferences[] $entityList
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