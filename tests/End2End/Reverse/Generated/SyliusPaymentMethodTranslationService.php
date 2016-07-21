<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class SyliusPaymentMethodTranslationService
{

    /**
     * @var SyliusPaymentMethodTranslationService
     */
    protected static $instance;

    /**
     * 
     * @return SyliusPaymentMethodTranslationService
     */
    public static function getInstance() : SyliusPaymentMethodTranslationService
    {
        if (self::$instance === null) {
            self::$instance = new SyliusPaymentMethodTranslationService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return SyliusPaymentMethodTranslation|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL sylius_payment_method_translation_SB_PK($id)", $connectionName);
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
        $connection->execute("CALL sylius_payment_method_translation_DB_PK($id)");
    }

    /**
     * 
     * @return SyliusPaymentMethodTranslation
     */
    public function newInstance() : SyliusPaymentMethodTranslation
    {
        return new SyliusPaymentMethodTranslation();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return SyliusPaymentMethodTranslation
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : SyliusPaymentMethodTranslation
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return SyliusPaymentMethodTranslation[]
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
     * @param SyliusPaymentMethodTranslation[] $entityList
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