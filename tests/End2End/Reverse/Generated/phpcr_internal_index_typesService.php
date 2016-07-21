<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class phpcr_internal_index_typesService
{

    /**
     * @var phpcr_internal_index_typesService
     */
    protected static $instance;

    /**
     * 
     * @return phpcr_internal_index_typesService
     */
    public static function getInstance() : phpcr_internal_index_typesService
    {
        if (self::$instance === null) {
            self::$instance = new phpcr_internal_index_typesService();
        }
        return self::$instance;
    }

    /**
     * @param string $type
     * @param int $node_id
     * @param string $connectionName
     * 
     * @return phpcr_internal_index_types|null
     */
    public function getEntityByPrimaryKey(string $type = null, int $node_id = null, string $connectionName = null)
    {
        if ($type === null || $node_id === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $type = Escaper::quoteString($connection, $type);
        $node_id = Escaper::quoteInt($node_id);
        $entityList = $this->executeStoredProcedure("CALL phpcr_internal_index_types_SB_PK($type,$node_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $type
     * @param int $node_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $type, int $node_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $type = Escaper::quoteString($connection, $type);
        $node_id = Escaper::quoteInt($node_id);
        $connection->execute("CALL phpcr_internal_index_types_DB_PK($type,$node_id)");
    }

    /**
     * 
     * @return phpcr_internal_index_types
     */
    public function newInstance() : phpcr_internal_index_types
    {
        return new phpcr_internal_index_types();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return phpcr_internal_index_types
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : phpcr_internal_index_types
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return phpcr_internal_index_types[]
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
     * @param phpcr_internal_index_types[] $entityList
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