<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class PhpcrTypePropsService
{

    /**
     * @var PhpcrTypePropsService
     */
    protected static $instance;

    /**
     * 
     * @return PhpcrTypePropsService
     */
    public static function getInstance() : PhpcrTypePropsService
    {
        if (self::$instance === null) {
            self::$instance = new PhpcrTypePropsService();
        }
        return self::$instance;
    }

    /**
     * @param int $nodeTypeId
     * @param string $name
     * @param string $connectionName
     * 
     * @return PhpcrTypeProps|null
     */
    public function getEntityByPrimaryKey(int $nodeTypeId = null, string $name = null, string $connectionName = null)
    {
        if ($nodeTypeId === null || $name === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $nodeTypeId = Escaper::quoteInt($nodeTypeId);
        $name = Escaper::quoteString($connection, $name);
        $entityList = $this->executeStoredProcedure("CALL phpcr_type_props_SB_PK($nodeTypeId,$name)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $nodeTypeId
     * @param string $name
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $nodeTypeId, string $name, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $nodeTypeId = Escaper::quoteInt($nodeTypeId);
        $name = Escaper::quoteString($connection, $name);
        $connection->execute("CALL phpcr_type_props_DB_PK($nodeTypeId,$name)");
    }

    /**
     * 
     * @return PhpcrTypeProps
     */
    public function newInstance() : PhpcrTypeProps
    {
        return new PhpcrTypeProps();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return PhpcrTypeProps
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : PhpcrTypeProps
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return PhpcrTypeProps[]
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
     * @param PhpcrTypeProps[] $entityList
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