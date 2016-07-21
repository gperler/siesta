<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_product_archetype_optionService
{

    /**
     * @var sylius_product_archetype_optionService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_product_archetype_optionService
     */
    public static function getInstance() : sylius_product_archetype_optionService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_product_archetype_optionService();
        }
        return self::$instance;
    }

    /**
     * @param int $product_archetype_id
     * @param int $option_id
     * @param string $connectionName
     * 
     * @return sylius_product_archetype_option|null
     */
    public function getEntityByPrimaryKey(int $product_archetype_id = null, int $option_id = null, string $connectionName = null)
    {
        if ($product_archetype_id === null || $option_id === null) {
            return null;
        }
        $product_archetype_id = Escaper::quoteInt($product_archetype_id);
        $option_id = Escaper::quoteInt($option_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_archetype_option_SB_PK($product_archetype_id,$option_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $product_archetype_id
     * @param int $option_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $product_archetype_id, int $option_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $product_archetype_id = Escaper::quoteInt($product_archetype_id);
        $option_id = Escaper::quoteInt($option_id);
        $connection->execute("CALL sylius_product_archetype_option_DB_PK($product_archetype_id,$option_id)");
    }

    /**
     * 
     * @return sylius_product_archetype_option
     */
    public function newInstance() : sylius_product_archetype_option
    {
        return new sylius_product_archetype_option();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_product_archetype_option
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_product_archetype_option
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_product_archetype_option[]
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
     * @param sylius_product_archetype_option[] $entityList
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