<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrTypeNodesService
{

    /**
     * @var PhpcrTypeNodesService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrTypeNodesService
     */
    public static function getInstance() : PhpcrTypeNodesService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrTypeNodesService();
        }
        return self::$instance;
    }

    /**
     * @param int $nodeTypeId
     * @param string $connectionName
     * 
     * @return PhpcrTypeNodes|null
     */
    public function getEntityByPrimaryKey(int $nodeTypeId = null, string $connectionName = null)
    {
        if ($nodeTypeId === null) {
            return null;
        }
        $nodeTypeId = Escaper::quoteInt($nodeTypeId);
        $entityList = $this->executeStoredProcedure("CALL phpcr_type_nodes_SB_PK($nodeTypeId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $nodeTypeId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $nodeTypeId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $nodeTypeId = Escaper::quoteInt($nodeTypeId);
        $connection->execute("CALL phpcr_type_nodes_DB_PK($nodeTypeId)");
    }

    /**
     * 
     * @return PhpcrTypeNodes
     */
    public function newInstance() : PhpcrTypeNodes
    {
        return new PhpcrTypeNodes();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrTypeNodes
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrTypeNodes
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrTypeNodes[]
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
     * @param PhpcrTypeNodes[] $entityList
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