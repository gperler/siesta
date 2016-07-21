<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_product_archetype_attributeService
{

    /**
     * @var sylius_product_archetype_attributeService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_product_archetype_attributeService
     */
    public static function getInstance() : sylius_product_archetype_attributeService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_product_archetype_attributeService();
        }
        return self::$instance;
    }

    /**
     * @param int $archetype_id
     * @param int $attribute_id
     * @param string $connectionName
     * 
     * @return sylius_product_archetype_attribute|null
     */
    public function getEntityByPrimaryKey(int $archetype_id = null, int $attribute_id = null, string $connectionName = null)
    {
        if ($archetype_id === null || $attribute_id === null) {
            return null;
        }
        $archetype_id = Escaper::quoteInt($archetype_id);
        $attribute_id = Escaper::quoteInt($attribute_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_archetype_attribute_SB_PK($archetype_id,$attribute_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $archetype_id
     * @param int $attribute_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $archetype_id, int $attribute_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $archetype_id = Escaper::quoteInt($archetype_id);
        $attribute_id = Escaper::quoteInt($attribute_id);
        $connection->execute("CALL sylius_product_archetype_attribute_DB_PK($archetype_id,$attribute_id)");
    }

    /**
     * 
     * @return sylius_product_archetype_attribute
     */
    public function newInstance() : sylius_product_archetype_attribute
    {
        return new sylius_product_archetype_attribute();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_product_archetype_attribute
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_product_archetype_attribute
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_product_archetype_attribute[]
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
     * @param sylius_product_archetype_attribute[] $entityList
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