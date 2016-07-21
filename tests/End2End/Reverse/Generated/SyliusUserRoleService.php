<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusUserRoleService
{

    /**
     * @var SyliusUserRoleService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusUserRoleService
     */
    public static function getInstance() : SyliusUserRoleService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusUserRoleService();
        }
        return self::$instance;
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @param string $connectionName
     * 
     * @return SyliusUserRole|null
     */
    public function getEntityByPrimaryKey(int $userId = null, int $roleId = null, string $connectionName = null)
    {
        if ($userId === null || $roleId === null) {
            return null;
        }
        $userId = Escaper::quoteInt($userId);
        $roleId = Escaper::quoteInt($roleId);
        $entityList = $this->executeStoredProcedure("CALL sylius_user_role_SB_PK($userId,$roleId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $userId
     * @param int $roleId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $userId, int $roleId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $userId = Escaper::quoteInt($userId);
        $roleId = Escaper::quoteInt($roleId);
        $connection->execute("CALL sylius_user_role_DB_PK($userId,$roleId)");
    }

    /**
     * 
     * @return SyliusUserRole
     */
    public function newInstance() : SyliusUserRole
    {
        return new SyliusUserRole();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusUserRole
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusUserRole
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusUserRole[]
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
     * @param SyliusUserRole[] $entityList
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