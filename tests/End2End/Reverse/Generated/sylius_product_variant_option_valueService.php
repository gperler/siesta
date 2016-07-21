<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_product_variant_option_valueService
{

    /**
     * @var sylius_product_variant_option_valueService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_product_variant_option_valueService
     */
    public static function getInstance() : sylius_product_variant_option_valueService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_product_variant_option_valueService();
        }
        return self::$instance;
    }

    /**
     * @param int $variant_id
     * @param int $option_value_id
     * @param string $connectionName
     * 
     * @return sylius_product_variant_option_value|null
     */
    public function getEntityByPrimaryKey(int $variant_id = null, int $option_value_id = null, string $connectionName = null)
    {
        if ($variant_id === null || $option_value_id === null) {
            return null;
        }
        $variant_id = Escaper::quoteInt($variant_id);
        $option_value_id = Escaper::quoteInt($option_value_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_variant_option_value_SB_PK($variant_id,$option_value_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $variant_id
     * @param int $option_value_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $variant_id, int $option_value_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $variant_id = Escaper::quoteInt($variant_id);
        $option_value_id = Escaper::quoteInt($option_value_id);
        $connection->execute("CALL sylius_product_variant_option_value_DB_PK($variant_id,$option_value_id)");
    }

    /**
     * 
     * @return sylius_product_variant_option_value
     */
    public function newInstance() : sylius_product_variant_option_value
    {
        return new sylius_product_variant_option_value();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_product_variant_option_value
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_product_variant_option_value
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_product_variant_option_value[]
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
     * @param sylius_product_variant_option_value[] $entityList
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