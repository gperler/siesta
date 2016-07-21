<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrNodesReferencesService
{

    /**
     * @var PhpcrNodesReferencesService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrNodesReferencesService
     */
    public static function getInstance() : PhpcrNodesReferencesService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrNodesReferencesService();
        }
        return self::$instance;
    }

    /**
     * @param int $sourceId
     * @param string $sourcePropertyName
     * @param int $targetId
     * @param string $connectionName
     * 
     * @return PhpcrNodesReferences|null
     */
    public function getEntityByPrimaryKey(int $sourceId = null, string $sourcePropertyName = null, int $targetId = null, string $connectionName = null)
    {
        if ($sourceId === null || $sourcePropertyName === null || $targetId === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $sourceId = Escaper::quoteInt($sourceId);
        $sourcePropertyName = Escaper::quoteString($connection, $sourcePropertyName);
        $targetId = Escaper::quoteInt($targetId);
        $entityList = $this->executeStoredProcedure("CALL phpcr_nodes_references_SB_PK($sourceId,$sourcePropertyName,$targetId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $sourceId
     * @param string $sourcePropertyName
     * @param int $targetId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $sourceId, string $sourcePropertyName, int $targetId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $sourceId = Escaper::quoteInt($sourceId);
        $sourcePropertyName = Escaper::quoteString($connection, $sourcePropertyName);
        $targetId = Escaper::quoteInt($targetId);
        $connection->execute("CALL phpcr_nodes_references_DB_PK($sourceId,$sourcePropertyName,$targetId)");
    }

    /**
     * 
     * @return PhpcrNodesReferences
     */
    public function newInstance() : PhpcrNodesReferences
    {
        return new PhpcrNodesReferences();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrNodesReferences
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrNodesReferences
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrNodesReferences[]
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
     * @param PhpcrNodesReferences[] $entityList
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