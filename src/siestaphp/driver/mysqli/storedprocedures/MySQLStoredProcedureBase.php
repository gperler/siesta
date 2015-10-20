<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLConnection;
use siestaphp\driver\mysqli\replication\Replication;

/**
 * Class MySQLStoredProcedureBase
 * @package siestaphp\driver\mysqli\storedprocedures
 */
abstract class MySQLStoredProcedureBase implements MySQLStoredProcedure
{

    const CREATE_PROCEDURE = "CREATE PROCEDURE";
    const READS_DATA = " NOT DETERMINISTIC READS SQL DATA SQL SECURITY INVOKER ";
    const MODIFIES_DATA = " NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY INVOKER ";

    /**
     * @var EntityGeneratorSource
     */
    protected $entitySource;

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
     * @param EntityGeneratorSource $source
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $source, $replication)
    {
        $this->entitySource = $source;
        $this->replication = $replication;
    }

    /**
     *
     */
    protected function determineTableNames()
    {
        $this->tableName = $this->entitySource->getTable();
        $this->memoryTableName = Replication::getReplicationTableName($this->tableName);
    }

    /**
     * @return string
     */
    public function buildCreateProcedureStatement()
    {
        $config = ($this->modifies) ? self::MODIFIES_DATA : self::READS_DATA;

        $definition = self::CREATE_PROCEDURE . " " . $this->quote($this->name) . " " . $this->signature . " " . $config . " BEGIN " . $this->statement . " END;";

        return $definition;
    }

    /**
     * @return string
     */
    public function buildProcedureDropStatement()
    {
        return "DROP PROCEDURE IF EXISTS " . $this->quote($this->name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function quote($name)
    {
        return MySQLConnection::quote($name);
    }

}