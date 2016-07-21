<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusProductVariantOptionValueService
{

    /**
     * @var SyliusProductVariantOptionValueService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusProductVariantOptionValueService
     */
    public static function getInstance() : SyliusProductVariantOptionValueService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusProductVariantOptionValueService();
        }
        return self::$instance;
    }

    /**
     * @param int $variantId
     * @param int $optionValueId
     * @param string $connectionName
     * 
     * @return SyliusProductVariantOptionValue|null
     */
    public function getEntityByPrimaryKey(int $variantId = null, int $optionValueId = null, string $connectionName = null)
    {
        if ($variantId === null || $optionValueId === null) {
            return null;
        }
        $variantId = Escaper::quoteInt($variantId);
        $optionValueId = Escaper::quoteInt($optionValueId);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_variant_option_value_SB_PK($variantId,$optionValueId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $variantId
     * @param int $optionValueId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $variantId, int $optionValueId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $variantId = Escaper::quoteInt($variantId);
        $optionValueId = Escaper::quoteInt($optionValueId);
        $connection->execute("CALL sylius_product_variant_option_value_DB_PK($variantId,$optionValueId)");
    }

    /**
     * 
     * @return SyliusProductVariantOptionValue
     */
    public function newInstance() : SyliusProductVariantOptionValue
    {
        return new SyliusProductVariantOptionValue();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusProductVariantOptionValue
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusProductVariantOptionValue
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusProductVariantOptionValue[]
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
     * @param SyliusProductVariantOptionValue[] $entityList
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