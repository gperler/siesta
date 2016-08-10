<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CustomStoredProcedure\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class E2ECustomStoredProcedureService
{

    /**
     * @var E2ECustomStoredProcedureService
     */
    protected static $instance;

    /**
     * 
     * @return E2ECustomStoredProcedureService
     */
    public static function getInstance() : E2ECustomStoredProcedureService
    {
        if (self::$instance === null) {
            self::$instance = new E2ECustomStoredProcedureService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return E2ECustomStoredProcedure|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL E2ECustomStoredProcedure_SB_PK($id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL E2ECustomStoredProcedure_DB_PK($id)");
    }

    /**
     * @param string $stringParam
     * @param int $intParam
     * @param string $connectionName
     * 
     * @return E2ECustomStoredProcedure|null
     */
    public function getSingle(string $stringParam = null, int $intParam = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $stringParam = Escaper::quoteString($connection, $stringParam);
        $intParam = Escaper::quoteInt($intParam);
        $spCall = "CALL E2ECustomStoredProcedure_getSingle($stringParam,$intParam)";
        $entityList = $this->executeStoredProcedure($spCall);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $stringParam
     * @param int $intParam
     * @param string $connectionName
     * 
     * @return E2ECustomStoredProcedure[]
     */
    public function getList(string $stringParam = null, int $intParam = null, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $stringParam = Escaper::quoteString($connection, $stringParam);
        $intParam = Escaper::quoteInt($intParam);
        $spCall = "CALL E2ECustomStoredProcedure_getList($stringParam,$intParam)";
        return $this->executeStoredProcedure($spCall);
    }

    /**
     * @param string $stringParam
     * @param int $intParam
     * @param string $connectionName
     * 
     * @return ResultSet
     */
    public function getResultSet(string $stringParam = null, int $intParam = null, string $connectionName = null) : ResultSet
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $stringParam = Escaper::quoteString($connection, $stringParam);
        $intParam = Escaper::quoteInt($intParam);
        $spCall = "CALL E2ECustomStoredProcedure_getResultSet($stringParam,$intParam)";
        return $connection->executeStoredProcedure($spCall);
    }

    /**
     * @param string $stringParam
     * @param int $intParam
     * @param string $connectionName
     * 
     * @return void
     */
    public function updateTable(string $stringParam = null, int $intParam = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $stringParam = Escaper::quoteString($connection, $stringParam);
        $intParam = Escaper::quoteInt($intParam);
        $spCall = "CALL E2ECustomStoredProcedure_updateTable($stringParam,$intParam)";
        $connection->execute($spCall);
    }

    /**
     * @param string $connectionName
     * 
     * @return E2ECustomStoredProcedure[]
     */
    public function getListNoParam(string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $spCall = "CALL E2ECustomStoredProcedure_getListNoParam()";
        return $this->executeStoredProcedure($spCall);
    }

    /**
     * 
     * @return E2ECustomStoredProcedure
     */
    public function newInstance() : E2ECustomStoredProcedure
    {
        return new E2ECustomStoredProcedure();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return E2ECustomStoredProcedure
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : E2ECustomStoredProcedure
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return E2ECustomStoredProcedure[]
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
     * @param E2ECustomStoredProcedure[] $entityList
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