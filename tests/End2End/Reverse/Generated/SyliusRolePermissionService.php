<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusRolePermissionService
{

    /**
     * @var SyliusRolePermissionService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusRolePermissionService
     */
    public static function getInstance() : SyliusRolePermissionService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusRolePermissionService();
        }
        return self::$instance;
    }

    /**
     * @param int $roleId
     * @param int $permissionId
     * @param string $connectionName
     * 
     * @return SyliusRolePermission|null
     */
    public function getEntityByPrimaryKey(int $roleId = null, int $permissionId = null, string $connectionName = null)
    {
        if ($roleId === null || $permissionId === null) {
            return null;
        }
        $roleId = Escaper::quoteInt($roleId);
        $permissionId = Escaper::quoteInt($permissionId);
        $entityList = $this->executeStoredProcedure("CALL sylius_role_permission_SB_PK($roleId,$permissionId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $roleId
     * @param int $permissionId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $roleId, int $permissionId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $roleId = Escaper::quoteInt($roleId);
        $permissionId = Escaper::quoteInt($permissionId);
        $connection->execute("CALL sylius_role_permission_DB_PK($roleId,$permissionId)");
    }

    /**
     * 
     * @return SyliusRolePermission
     */
    public function newInstance() : SyliusRolePermission
    {
        return new SyliusRolePermission();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusRolePermission
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusRolePermission
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusRolePermission[]
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
     * @param SyliusRolePermission[] $entityList
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