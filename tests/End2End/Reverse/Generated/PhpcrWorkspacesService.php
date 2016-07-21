<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrWorkspacesService
{

    /**
     * @var PhpcrWorkspacesService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrWorkspacesService
     */
    public static function getInstance() : PhpcrWorkspacesService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrWorkspacesService();
        }
        return self::$instance;
    }

    /**
     * @param string $name
     * @param string $connectionName
     * 
     * @return PhpcrWorkspaces|null
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
     * @return PhpcrWorkspaces
     */
    public function newInstance() : PhpcrWorkspaces
    {
        return new PhpcrWorkspaces();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrWorkspaces
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrWorkspaces
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrWorkspaces[]
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
     * @param PhpcrWorkspaces[] $entityList
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