<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ProductRelatedService
{

    /**
     * @var ProductRelatedService
     */
    protected static $instance;

    /**
     * 
     * @return ProductRelatedService
     */
    public static function getInstance() : ProductRelatedService
    {
        if (self::$instance === null) {
            self::$instance = new ProductRelatedService();
        }
        return self::$instance;
    }

    /**
     * 
     * @return ProductRelated
     */
    public function newInstance() : ProductRelated
    {
        return new ProductRelated();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ProductRelated
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ProductRelated
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ProductRelated[]
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
     * @param ProductRelated[] $entityList
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