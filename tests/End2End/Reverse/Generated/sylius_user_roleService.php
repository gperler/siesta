<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_user_roleService
{

    /**
     * @var sylius_user_roleService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_user_roleService
     */
    public static function getInstance() : sylius_user_roleService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_user_roleService();
        }
        return self::$instance;
    }

    /**
     * @param int $user_id
     * @param int $role_id
     * @param string $connectionName
     * 
     * @return sylius_user_role|null
     */
    public function getEntityByPrimaryKey(int $user_id = null, int $role_id = null, string $connectionName = null)
    {
        if ($user_id === null || $role_id === null) {
            return null;
        }
        $user_id = Escaper::quoteInt($user_id);
        $role_id = Escaper::quoteInt($role_id);
        $entityList = $this->executeStoredProcedure("CALL sylius_user_role_SB_PK($user_id,$role_id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $user_id
     * @param int $role_id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $user_id, int $role_id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $user_id = Escaper::quoteInt($user_id);
        $role_id = Escaper::quoteInt($role_id);
        $connection->execute("CALL sylius_user_role_DB_PK($user_id,$role_id)");
    }

    /**
     * 
     * @return sylius_user_role
     */
    public function newInstance() : sylius_user_role
    {
        return new sylius_user_role();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_user_role
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_user_role
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_user_role[]
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
     * @param sylius_user_role[] $entityList
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