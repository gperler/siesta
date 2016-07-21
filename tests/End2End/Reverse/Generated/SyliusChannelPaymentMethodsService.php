<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusChannelPaymentMethodsService
{

    /**
     * @var SyliusChannelPaymentMethodsService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusChannelPaymentMethodsService
     */
    public static function getInstance() : SyliusChannelPaymentMethodsService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusChannelPaymentMethodsService();
        }
        return self::$instance;
    }

    /**
     * @param int $channelId
     * @param int $paymentMethodId
     * @param string $connectionName
     * 
     * @return SyliusChannelPaymentMethods|null
     */
    public function getEntityByPrimaryKey(int $channelId = null, int $paymentMethodId = null, string $connectionName = null)
    {
        if ($channelId === null || $paymentMethodId === null) {
            return null;
        }
        $channelId = Escaper::quoteInt($channelId);
        $paymentMethodId = Escaper::quoteInt($paymentMethodId);
        $entityList = $this->executeStoredProcedure("CALL sylius_channel_payment_methods_SB_PK($channelId,$paymentMethodId)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $channelId
     * @param int $paymentMethodId
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $channelId, int $paymentMethodId, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $channelId = Escaper::quoteInt($channelId);
        $paymentMethodId = Escaper::quoteInt($paymentMethodId);
        $connection->execute("CALL sylius_channel_payment_methods_DB_PK($channelId,$paymentMethodId)");
    }

    /**
     * 
     * @return SyliusChannelPaymentMethods
     */
    public function newInstance() : SyliusChannelPaymentMethods
    {
        return new SyliusChannelPaymentMethods();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusChannelPaymentMethods
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusChannelPaymentMethods
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusChannelPaymentMethods[]
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
     * @param SyliusChannelPaymentMethods[] $entityList
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