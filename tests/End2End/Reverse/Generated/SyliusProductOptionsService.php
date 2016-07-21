<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusProductOptionsService
{

    /**
     * @var SyliusProductOptionsService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusProductOptionsService
     */
    public static function getInstance() : SyliusProductOptionsService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusProductOptionsService();
        }
        return self::$instance;
    }

    /**
     * @param int $productId
     * @param int $optionId
     * @param string $connectionName
     * 
     * @return SyliusProductOptions|null
     */
    public function getEntityByPrimaryKey(int $productId = null, int $optionId = null, string $connectionName = null)
    {
        if ($productId === null || $optionId === null) {
            return null;
        }
        $productId = Escaper::quoteInt($productId);
        $optionId = Escaper::quoteInt($optionId);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_options_SB_PK($productId,$optionId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $productId
     * @param int $optionId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $productId, int $optionId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $productId = Escaper::quoteInt($productId);
        $optionId = Escaper::quoteInt($optionId);
        $connection->execute("CALL sylius_product_options_DB_PK($productId,$optionId)");
    }

    /**
     * 
     * @return SyliusProductOptions
     */
    public function newInstance() : SyliusProductOptions
    {
        return new SyliusProductOptions();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusProductOptions
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusProductOptions
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusProductOptions[]
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
     * @param SyliusProductOptions[] $entityList
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