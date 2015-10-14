<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\Connection;
use siestaphp\driver\mysqli\MySQLConnection;
use siestaphp\driver\mysqli\replication\Replication;

/**
 * Class StoredProcedureBase
 * @package siestaphp\driver\mysqli\storedprocedures
 */
abstract class StoredProcedureBase implements StoredProcedure
{


    const CREATE_PROCEDURE = "CREATE PROCEDURE";
    const READS_DATA = " NOT DETERMINISTIC READS SQL DATA SQL SECURITY INVOKER ";
    const MODIFIES_DATA = " NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY INVOKER ";


    /**
     * @var EntityGeneratorSource
     */
    protected $entityDatabaseSource;

    /**
     * @var string
     */
    protected $signature;

    /**
     * @var bool
     */
    protected $modifies;

    /**
     * @var string
     */
    protected $name;


    /**
     * @var string
     */
    protected $statement;

    /**
     * @var string
     */
    protected $tableName;


    /**
     * @var string
     */
    protected $memoryTableName;

    /**
     * @var bool
     */
    protected $replication;


    /**
     * @param EntityGeneratorSource $eds
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $eds, $replication)
    {
        $this->entityDatabaseSource = $eds;
        $this->replication = $replication;
    }

    /**
     *
     */
    protected function determineTableNames()
    {
        $this->tableName = $this->entityDatabaseSource->getTable();
        $this->memoryTableName = Replication::getReplicationTableName($this->tableName);
    }

    /**
     * @param Connection $connection
     *
     */
    protected function executeProcedureBuild(Connection $connection)
    {

        $config = ($this->modifies) ? self::MODIFIES_DATA : self::READS_DATA;

        $definition = self::CREATE_PROCEDURE . " " . $this->quote($this->name) . " " . $this->signature . " " . $config . " BEGIN " . $this->statement . " END;";

        $connection->query($definition);
    }

    /**
     * @param Connection $connection
     */
    protected function executeProcedureDrop(Connection $connection)
    {
        $connection->query("DROP PROCEDURE IF EXISTS " . $this->quote($this->name));
    }


    /**
     * @param $name
     * @return string
     */
    protected function quote($name)
    {
        return MySQLConnection::quote($name);
    }



}