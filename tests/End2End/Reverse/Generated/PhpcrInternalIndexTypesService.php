<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrInternalIndexTypesService
{

    /**
     * @var PhpcrInternalIndexTypesService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrInternalIndexTypesService
     */
    public static function getInstance() : PhpcrInternalIndexTypesService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrInternalIndexTypesService();
        }
        return self::$instance;
    }

    /**
     * @param string $type
     * @param int $nodeId
     * @param string $connectionName
     * 
     * @return PhpcrInternalIndexTypes|null
     */
    public function getEntityByPrimaryKey(string $type = null, int $nodeId = null, string $connectionName = null)
    {
        if ($type === null || $nodeId === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $type = Escaper::quoteString($connection, $type);
        $nodeId = Escaper::quoteInt($nodeId);
        $entityList = $this->executeStoredProcedure("CALL phpcr_internal_index_types_SB_PK($type,$nodeId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $type
     * @param int $nodeId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $type, int $nodeId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $type = Escaper::quoteString($connection, $type);
        $nodeId = Escaper::quoteInt($nodeId);
        $connection->execute("CALL phpcr_internal_index_types_DB_PK($type,$nodeId)");
    }

    /**
     * 
     * @return PhpcrInternalIndexTypes
     */
    public function newInstance() : PhpcrInternalIndexTypes
    {
        return new PhpcrInternalIndexTypes();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrInternalIndexTypes
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrInternalIndexTypes
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrInternalIndexTypes[]
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
     * @param PhpcrInternalIndexTypes[] $entityList
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