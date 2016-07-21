<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusCustomerGroupService
{

    /**
     * @var SyliusCustomerGroupService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusCustomerGroupService
     */
    public static function getInstance() : SyliusCustomerGroupService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusCustomerGroupService();
        }
        return self::$instance;
    }

    /**
     * @param int $customerId
     * @param int $groupId
     * @param string $connectionName
     * 
     * @return SyliusCustomerGroup|null
     */
    public function getEntityByPrimaryKey(int $customerId = null, int $groupId = null, string $connectionName = null)
    {
        if ($customerId === null || $groupId === null) {
            return null;
        }
        $customerId = Escaper::quoteInt($customerId);
        $groupId = Escaper::quoteInt($groupId);
        $entityList = $this->executeStoredProcedure("CALL sylius_customer_group_SB_PK($customerId,$groupId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $customerId
     * @param int $groupId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $customerId, int $groupId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $customerId = Escaper::quoteInt($customerId);
        $groupId = Escaper::quoteInt($groupId);
        $connection->execute("CALL sylius_customer_group_DB_PK($customerId,$groupId)");
    }

    /**
     * 
     * @return SyliusCustomerGroup
     */
    public function newInstance() : SyliusCustomerGroup
    {
        return new SyliusCustomerGroup();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusCustomerGroup
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusCustomerGroup
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusCustomerGroup[]
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
     * @param SyliusCustomerGroup[] $entityList
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