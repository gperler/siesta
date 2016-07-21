<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_customer_groupService
{

    /**
     * @var sylius_customer_groupService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_customer_groupService
     */
    public static function getInstance() : sylius_customer_groupService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_customer_groupService();
        }
        return self::$instance;
    }

    /**
     * @param int $customer_id
     * @param int $group_id
     * @param string $connectionName
     * 
     * @return sylius_customer_group|null
     */
    public function getEntityByPrimaryKey(int $customer_id = null, int $group_id = null, string $connectionName = null)
    {
        if ($customer_id === null || $group_id === null) {
            return null;
        }
        $customer_id = Escaper::quoteInt($customer_id);
        $group_id = Escaper::quoteInt($group_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_customer_group_SB_PK($customer_id,$group_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $customer_id
     * @param int $group_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $customer_id, int $group_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $customer_id = Escaper::quoteInt($customer_id);
        $group_id = Escaper::quoteInt($group_id);
        $connection->execute("CALL sylius_customer_group_DB_PK($customer_id,$group_id)");
    }

    /**
     * 
     * @return sylius_customer_group
     */
    public function newInstance() : sylius_customer_group
    {
        return new sylius_customer_group();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_customer_group
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_customer_group
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_customer_group[]
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
     * @param sylius_customer_group[] $entityList
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