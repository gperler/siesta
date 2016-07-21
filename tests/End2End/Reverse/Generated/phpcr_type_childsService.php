<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class phpcr_type_childsService
{

    /**
     * @var phpcr_type_childsService
     */
    protected static $instance;

    /**
     * 
     * @return phpcr_type_childsService
     */
    public static function getInstance() : phpcr_type_childsService
    {
        if (self::$instance === null) {
            self::$instance = new phpcr_type_childsService();
        }
        return self::$instance;
    }

    /**
     * 
     * @return phpcr_type_childs
     */
    public function newInstance() : phpcr_type_childs
    {
        return new phpcr_type_childs();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return phpcr_type_childs
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : phpcr_type_childs
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return phpcr_type_childs[]
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
     * @param phpcr_type_childs[] $entityList
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