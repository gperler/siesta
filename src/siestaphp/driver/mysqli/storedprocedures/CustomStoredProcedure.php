<?php


namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\driver\Connection;

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
     * @param Connection $connection
     */
    public function createProcedure(Connection $connection)
    {

        $this->determineTableNames();

        $this->buildName();

        $this->buildSignature();

        $this->buildStatement();

        $this->executeProcedureDrop($connection);

        $this->executeProcedureBuild($connection);
    }

    /**
     * @param Connection $connection
     */
    public function dropProcedure(Connection $connection)
    {
        $this->buildName();
        $this->executeProcedureDrop($connection);
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