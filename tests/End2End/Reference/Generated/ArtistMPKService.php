<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reference\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class ArtistMPKService
{

    /**
     * @var ArtistMPKService
     */
    protected static $instance;

    /**
     * 
     * @return ArtistMPKService
     */
    public static function getInstance() : ArtistMPKService
    {
        if (self::$instance === null) {
            self::$instance = new ArtistMPKService();
        }
        return self::$instance;
    }

    /**
     * @param string $id_1
     * @param string $id_2
     * @param string $connectionName
     * 
     * @return ArtistMPK|null
     */
    public function getEntityByPrimaryKey(string $id_1 = null, string $id_2 = null, string $connectionName = null)
    {
        if ($id_1 === null || $id_2 === null) {
            return null;
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $id_1 = Escaper::quoteString($connection, $id_1);
        $id_2 = Escaper::quoteString($connection, $id_2);
        $entityList = $this->executeStoredProcedure("CALL ArtistMPK_SB_PK($id_1,$id_2)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param string $id_1
     * @param string $id_2
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(string $id_1, string $id_2, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id_1 = Escaper::quoteString($connection, $id_1);
        $id_2 = Escaper::quoteString($connection, $id_2);
        $connection->execute("CALL ArtistMPK_DB_PK($id_1,$id_2)");
    }

    /**
     * 
     * @return ArtistMPK
     */
    public function newInstance() : ArtistMPK
    {
        return new ArtistMPK();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return ArtistMPK
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : ArtistMPK
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return ArtistMPK[]
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
     * @param ArtistMPK[] $entityList
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