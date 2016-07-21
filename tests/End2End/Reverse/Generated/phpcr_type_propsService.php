<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class phpcr_type_propsService
{

    /**
     * @var phpcr_type_propsService
     */
    protected static $instance;

    /**
     * 
     * @return phpcr_type_propsService
     */
    public static function getInstance() : phpcr_type_propsService
    {
        if (self::$instance === null) {
            self::$instance = new phpcr_type_propsService();
        }
        return self::$instance;
    }

    /**
     * @param int $node_type_id
     * @param string $name
     * @param string $connectionName
     * 
     * @return phpcr_type_props|null
     */
    public function getEntityByPrimaryKey(int $node_type_id = null, string $name = null, string $connectionName = null)
    {
        if ($node_type_id === null || $name === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $node_type_id = Escaper::quoteInt($node_type_id);
        $name = Escaper::quoteString($connection, $name);
        $entityList = $this->executeStoredProcedure("CALL phpcr_type_props_SB_PK($node_type_id,$name)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $node_type_id
     * @param string $name
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $node_type_id, string $name, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $node_type_id = Escaper::quoteInt($node_type_id);
        $name = Escaper::quoteString($connection, $name);
        $connection->execute("CALL phpcr_type_props_DB_PK($node_type_id,$name)");
    }

    /**
     * 
     * @return phpcr_type_props
     */
    public function newInstance() : phpcr_type_props
    {
        return new phpcr_type_props();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return phpcr_type_props
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : phpcr_type_props
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return phpcr_type_props[]
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
     * @param phpcr_type_props[] $entityList
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