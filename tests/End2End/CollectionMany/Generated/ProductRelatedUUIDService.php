<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ProductRelatedUUIDService
{

    /**
     * @var ProductRelatedUUIDService
     */
    protected static $instance;

    /**
     * 
     * @return ProductRelatedUUIDService
     */
    public static function getInstance() : ProductRelatedUUIDService
    {
        if (self::$instance === null) {
            self::$instance = new ProductRelatedUUIDService();
        }
        return self::$instance;
    }

    /**
     * 
     * @return ProductRelatedUUID
     */
    public function newInstance() : ProductRelatedUUID
    {
        return new ProductRelatedUUID();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ProductRelatedUUID
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ProductRelatedUUID
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ProductRelatedUUID[]
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
     * @param ProductRelatedUUID[] $entityList
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