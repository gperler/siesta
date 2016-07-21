<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class phpcr_workspacesService
{

    /**
     * @var phpcr_workspacesService
     */
    protected static $instance;

    /**
     * 
     * @return phpcr_workspacesService
     */
    public static function getInstance() : phpcr_workspacesService
    {
        if (self::$instance === null) {
            self::$instance = new phpcr_workspacesService();
        }
        return self::$instance;
    }

    /**
     * @param string $name
     * @param string $connectionName
     * 
     * @return phpcr_workspaces|null
     */
    public function getEntityByPrimaryKey(string $name = null, string $connectionName = null)
    {
        if ($name === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $name = Escaper::quoteString($connection, $name);
        $entityList = $this->executeStoredProcedure("CALL phpcr_workspaces_SB_PK($name)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $name
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $name, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $name = Escaper::quoteString($connection, $name);
        $connection->execute("CALL phpcr_workspaces_DB_PK($name)");
    }

    /**
     * 
     * @return phpcr_workspaces
     */
    public function newInstance() : phpcr_workspaces
    {
        return new phpcr_workspaces();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return phpcr_workspaces
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : phpcr_workspaces
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return phpcr_workspaces[]
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
     * @param phpcr_workspaces[] $entityList
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