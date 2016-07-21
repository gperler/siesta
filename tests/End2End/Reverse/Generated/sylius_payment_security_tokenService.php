<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class sylius_payment_security_tokenService
{

    /**
     * @var sylius_payment_security_tokenService
     */
    protected static $instance;

    /**
     * 
     * @return sylius_payment_security_tokenService
     */
    public static function getInstance() : sylius_payment_security_tokenService
    {
        if (self::$instance === null) {
            self::$instance = new sylius_payment_security_tokenService();
        }
        return self::$instance;
    }

    /**
     * @param string $hash
     * @param string $connectionName
     * 
     * @return sylius_payment_security_token|null
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
     * @return sylius_payment_security_token
     */
    public function newInstance() : sylius_payment_security_token
    {
        return new sylius_payment_security_token();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return sylius_payment_security_token
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : sylius_payment_security_token
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return sylius_payment_security_token[]
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
     * @param sylius_payment_security_token[] $entityList
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