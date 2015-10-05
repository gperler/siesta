<?php


namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureDatabaseSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\driver\Driver;
use siestaphp\driver\mysqli\MySQLDriver;

/**
 * Class CustomStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class CustomStoredProcedure extends StoredProcedureBase
{

    const TABLE_PLACE_HOLDER = "!TABLE!";

    /**
     * @var StoredProcedureSource
     */
    protected $storedProcedureSource;

    /**
     * @param StoredProcedureSource $source
     * @param EntityDatabaseSource $eds
     * @param $replication
     */
    public function __construct(StoredProcedureSource $source, EntityDatabaseSource $eds, $replication)
    {
        parent::__construct($eds, $replication);

        $this->storedProcedureSource = $source;

        $this->modifies = $this->storedProcedureSource->modifies();
    }

    /**
     * @param Driver $driver
     */
    public function createProcedure(Driver $driver)
    {

        $this->determineTableNames();

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();

        $this->executeProcedureDrop($driver);

        $this->executeProcedureBuild($driver);
    }

    /**
     * @param Driver $driver
     */
    public function dropProcedure(Driver $driver)
    {
        $this->buildName();
        $this->executeProcedureDrop($driver);
    }

    protected function buildName()
    {
        $this->name = $this->storedProcedureSource->getDatabaseName();
    }

    protected function buildSignature()
    {
        $this->signature = "(";
        foreach ($this->storedProcedureSource->getParameterList() as $parameter) {
            $this->signature .= "IN " . $parameter->getStoredProcedureName() . " " . $parameter->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",") . ")";
    }

    protected function buildStatement()
    {
        $sql = $this->storedProcedureSource->getSql("mysql");

        $this->statement = $this->replaceTable($sql, $this->tableName);

    }

    /**
     * @param $sql
     * @param $tableName
     *
     * @return mixed
     */
    protected function replaceTable($sql, $tableName)
    {
        return str_replace(self::TABLE_PLACE_HOLDER, $tableName, $sql);
    }

}