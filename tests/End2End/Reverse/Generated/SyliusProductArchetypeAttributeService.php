<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusProductArchetypeAttributeService
{

    /**
     * @var SyliusProductArchetypeAttributeService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusProductArchetypeAttributeService
     */
    public static function getInstance() : SyliusProductArchetypeAttributeService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusProductArchetypeAttributeService();
        }
        return self::$instance;
    }

    /**
     * @param int $archetypeId
     * @param int $attributeId
     * @param string $connectionName
     * 
     * @return SyliusProductArchetypeAttribute|null
     */
    public function getEntityByPrimaryKey(int $archetypeId = null, int $attributeId = null, string $connectionName = null)
    {
        if ($archetypeId === null || $attributeId === null) {
            return null;
        }
        $archetypeId = Escaper::quoteInt($archetypeId);
        $attributeId = Escaper::quoteInt($attributeId);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_archetype_attribute_SB_PK($archetypeId,$attributeId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $archetypeId
     * @param int $attributeId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $archetypeId, int $attributeId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $archetypeId = Escaper::quoteInt($archetypeId);
        $attributeId = Escaper::quoteInt($attributeId);
        $connection->execute("CALL sylius_product_archetype_attribute_DB_PK($archetypeId,$attributeId)");
    }

    /**
     * 
     * @return SyliusProductArchetypeAttribute
     */
    public function newInstance() : SyliusProductArchetypeAttribute
    {
        return new SyliusProductArchetypeAttribute();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusProductArchetypeAttribute
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusProductArchetypeAttribute
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusProductArchetypeAttribute[]
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
     * @param SyliusProductArchetypeAttribute[] $entityList
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