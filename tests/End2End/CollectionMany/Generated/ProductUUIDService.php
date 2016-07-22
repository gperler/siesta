<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ProductUUIDService
{

    /**
     * @var ProductUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return ProductUUIDService
     */
    public static function getInstance() : ProductUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new ProductUUIDService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return ProductUUID|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL ProductUUID_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL ProductUUID_DB_PK($id)");
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return ProductUUID[]
     */
    public function getProductUUIDJoinProductRelatedUUID(int $id, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        return $this->executeStoredProcedure("CALL ProductUUID_S_JOIN_ProductRelatedUUID_relatedPro($id)", $connectionName);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteProductUUIDJoinProductRelatedUUID(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL ProductUUID_D_JOIN_ProductRelatedUUID_relatedPro($id)");
    }

    /**
     * 
     * @return ProductUUID
     */
    public function newInstance() : ProductUUID
    {
        return new ProductUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ProductUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ProductUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ProductUUID[]
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
     * @param ProductUUID[] $entityList
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