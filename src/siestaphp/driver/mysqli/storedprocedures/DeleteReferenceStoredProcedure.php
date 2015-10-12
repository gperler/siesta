<?php


namespace siestaphp\driver\mysqli\storedprocedures;



use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\driver\Connection;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class DeleteReferenceStoredProcedure extends StoredProcedureBase
{

    protected $referenceSource;

    /**
     * @param EntityDatabaseSource $eds
     * @param ReferenceDatabaseSource $referenceSource
     * @param bool $replication
     */
    public function __construct(EntityDatabaseSource $eds, ReferenceDatabaseSource $referenceSource, $replication)
    {
        parent::__construct($eds, $replication);
        $this->referenceSource = $referenceSource;
    }

    /**
     * @param Connection $connection
     */
    public function createProcedure(Connection $connection)
    {

        $this->modifies = true;

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

    /**
     *
     */
    protected function buildName()
    {
        $this->name = $this->referenceSource->getStoredProcedureDeleterName();

    }


    /**
     *
     */
    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->referenceSource->getReferenceColumnList() as $column) {
            $this->signature .= "IN " . $column->getSQLParameterName() . " " . $column->getDatabaseType() . ",";
        }

        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }


    protected function buildStatement()
    {
        $where = "";
        foreach ($this->referenceSource->getReferenceColumnList() as $column) {
            $where .= $column->getDatabaseName() . " = " . $column->getSQLParameterName() . " AND ";
        }

        $where = substr($where, 0, -5);
        $tableName = $this->quote($this->tableName);

        $this->statement = "DELETE FROM $tableName WHERE $where;";

    }


}