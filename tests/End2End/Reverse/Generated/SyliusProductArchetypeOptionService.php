<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusProductArchetypeOptionService
{

    /**
     * @var SyliusProductArchetypeOptionService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusProductArchetypeOptionService
     */
    public static function getInstance() : SyliusProductArchetypeOptionService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusProductArchetypeOptionService();
        }
        return self::$instance;
    }

    /**
     * @param int $productArchetypeId
     * @param int $optionId
     * @param string $connectionName
     * 
     * @return SyliusProductArchetypeOption|null
     */
    public function getEntityByPrimaryKey(int $productArchetypeId = null, int $optionId = null, string $connectionName = null)
    {
        if ($productArchetypeId === null || $optionId === null) {
            return null;
        }
        $productArchetypeId = Escaper::quoteInt($productArchetypeId);
        $optionId = Escaper::quoteInt($optionId);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_archetype_option_SB_PK($productArchetypeId,$optionId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $productArchetypeId
     * @param int $optionId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $productArchetypeId, int $optionId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $productArchetypeId = Escaper::quoteInt($productArchetypeId);
        $optionId = Escaper::quoteInt($optionId);
        $connection->execute("CALL sylius_product_archetype_option_DB_PK($productArchetypeId,$optionId)");
    }

    /**
     * 
     * @return SyliusProductArchetypeOption
     */
    public function newInstance() : SyliusProductArchetypeOption
    {
        return new SyliusProductArchetypeOption();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusProductArchetypeOption
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusProductArchetypeOption
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusProductArchetypeOption[]
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
     * @param SyliusProductArchetypeOption[] $entityList
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