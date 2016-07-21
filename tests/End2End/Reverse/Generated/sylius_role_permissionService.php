<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_role_permissionService
{

    /**
     * @var sylius_role_permissionService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_role_permissionService
     */
    public static function getInstance() : sylius_role_permissionService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_role_permissionService();
        }
        return self::$instance;
    }

    /**
     * @param int $role_id
     * @param int $permission_id
     * @param string $connectionName
     * 
     * @return sylius_role_permission|null
     */
    public function getEntityByPrimaryKey(int $role_id = null, int $permission_id = null, string $connectionName = null)
    {
        if ($role_id === null || $permission_id === null) {
            return null;
        }
        $role_id = Escaper::quoteInt($role_id);
        $permission_id = Escaper::quoteInt($permission_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_role_permission_SB_PK($role_id,$permission_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $role_id
     * @param int $permission_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $role_id, int $permission_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $role_id = Escaper::quoteInt($role_id);
        $permission_id = Escaper::quoteInt($permission_id);
        $connection->execute("CALL sylius_role_permission_DB_PK($role_id,$permission_id)");
    }

    /**
     * 
     * @return sylius_role_permission
     */
    public function newInstance() : sylius_role_permission
    {
        return new sylius_role_permission();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_role_permission
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_role_permission
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_role_permission[]
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
     * @param sylius_role_permission[] $entityList
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