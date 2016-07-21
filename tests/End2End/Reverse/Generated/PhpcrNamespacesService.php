<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrNamespacesService
{

    /**
     * @var PhpcrNamespacesService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrNamespacesService
     */
    public static function getInstance() : PhpcrNamespacesService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrNamespacesService();
        }
        return self::$instance;
    }

    /**
     * @param string $prefix
     * @param string $connectionName
     * 
     * @return PhpcrNamespaces|null
     */
    public function getEntityByPrimaryKey(string $prefix = null, string $connectionName = null)
    {
        if ($prefix === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $prefix = Escaper::quoteString($connection, $prefix);
        $entityList = $this->executeStoredProcedure("CALL phpcr_namespaces_SB_PK($prefix)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $prefix
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $prefix, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $prefix = Escaper::quoteString($connection, $prefix);
        $connection->execute("CALL phpcr_namespaces_DB_PK($prefix)");
    }

    /**
     * 
     * @return PhpcrNamespaces
     */
    public function newInstance() : PhpcrNamespaces
    {
        return new PhpcrNamespaces();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrNamespaces
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrNamespaces
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrNamespaces[]
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
     * @param PhpcrNamespaces[] $entityList
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