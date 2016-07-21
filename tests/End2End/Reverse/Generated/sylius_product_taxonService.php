<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_product_taxonService
{

    /**
     * @var sylius_product_taxonService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_product_taxonService
     */
    public static function getInstance() : sylius_product_taxonService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_product_taxonService();
        }
        return self::$instance;
    }

    /**
     * @param int $product_id
     * @param int $taxon_id
     * @param string $connectionName
     * 
     * @return sylius_product_taxon|null
     */
    public function getEntityByPrimaryKey(int $product_id = null, int $taxon_id = null, string $connectionName = null)
    {
        if ($product_id === null || $taxon_id === null) {
            return null;
        }
        $product_id = Escaper::quoteInt($product_id);
        $taxon_id = Escaper::quoteInt($taxon_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_taxon_SB_PK($product_id,$taxon_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $product_id
     * @param int $taxon_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $product_id, int $taxon_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $product_id = Escaper::quoteInt($product_id);
        $taxon_id = Escaper::quoteInt($taxon_id);
        $connection->execute("CALL sylius_product_taxon_DB_PK($product_id,$taxon_id)");
    }

    /**
     * 
     * @return sylius_product_taxon
     */
    public function newInstance() : sylius_product_taxon
    {
        return new sylius_product_taxon();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_product_taxon
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_product_taxon
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_product_taxon[]
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
     * @param sylius_product_taxon[] $entityList
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