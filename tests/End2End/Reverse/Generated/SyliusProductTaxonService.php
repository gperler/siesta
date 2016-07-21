<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusProductTaxonService
{

    /**
     * @var SyliusProductTaxonService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusProductTaxonService
     */
    public static function getInstance() : SyliusProductTaxonService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusProductTaxonService();
        }
        return self::$instance;
    }

    /**
     * @param int $productId
     * @param int $taxonId
     * @param string $connectionName
     * 
     * @return SyliusProductTaxon|null
     */
    public function getEntityByPrimaryKey(int $productId = null, int $taxonId = null, string $connectionName = null)
    {
        if ($productId === null || $taxonId === null) {
            return null;
        }
        $productId = Escaper::quoteInt($productId);
        $taxonId = Escaper::quoteInt($taxonId);
        $entityList = $this->executeStoredProcedure("CALL sylius_product_taxon_SB_PK($productId,$taxonId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $productId
     * @param int $taxonId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $productId, int $taxonId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $productId = Escaper::quoteInt($productId);
        $taxonId = Escaper::quoteInt($taxonId);
        $connection->execute("CALL sylius_product_taxon_DB_PK($productId,$taxonId)");
    }

    /**
     * 
     * @return SyliusProductTaxon
     */
    public function newInstance() : SyliusProductTaxon
    {
        return new SyliusProductTaxon();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusProductTaxon
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusProductTaxon
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusProductTaxon[]
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
     * @param SyliusProductTaxon[] $entityList
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