<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusPaymentSecurityTokenService
{

    /**
     * @var SyliusPaymentSecurityTokenService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusPaymentSecurityTokenService
     */
    public static function getInstance() : SyliusPaymentSecurityTokenService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusPaymentSecurityTokenService();
        }
        return self::$instance;
    }

    /**
     * @param string $hash
     * @param string $connectionName
     * 
     * @return SyliusPaymentSecurityToken|null
     */
    public function getEntityByPrimaryKey(string $hash = null, string $connectionName = null)
    {
        if ($hash === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $hash = Escaper::quoteString($connection, $hash);
        $entityList = $this->executeStoredProcedure("CALL sylius_payment_security_token_SB_PK($hash)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $hash
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $hash, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $hash = Escaper::quoteString($connection, $hash);
        $connection->execute("CALL sylius_payment_security_token_DB_PK($hash)");
    }

    /**
     * 
     * @return SyliusPaymentSecurityToken
     */
    public function newInstance() : SyliusPaymentSecurityToken
    {
        return new SyliusPaymentSecurityToken();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusPaymentSecurityToken
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusPaymentSecurityToken
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusPaymentSecurityToken[]
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
     * @param SyliusPaymentSecurityToken[] $entityList
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