<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_tax_categoryService
{

    /**
     * @var sylius_tax_categoryService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_tax_categoryService
     */
    public static function getInstance() : sylius_tax_categoryService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_tax_categoryService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return sylius_tax_category|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL sylius_tax_category_SB_PK($id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL sylius_tax_category_DB_PK($id)");
    }

    /**
     * 
     * @return sylius_tax_category
     */
    public function newInstance() : sylius_tax_category
    {
        return new sylius_tax_category();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_tax_category
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_tax_category
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_tax_category[]
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
     * @param sylius_tax_category[] $entityList
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