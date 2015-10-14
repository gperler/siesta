<?php


namespace siestaphp\driver\mysqli\storedprocedures;



use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\driver\Connection;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectReferenceStoredProcedure extends StoredProcedureBase
{

    protected $referenceSource;

    /**
     * @param EntityGeneratorSource $eds
     * @param ReferenceGeneratorSource $referenceSource
     * @param bool $replication
     */
    public function __construct(EntityGeneratorSource $eds, ReferenceGeneratorSource $referenceSource, $replication)
    {
        parent::__construct($eds, $replication);
        $this->referenceSource = $referenceSource;
    }

    /**
     * @param Connection $connection
     */
    public function createProcedure(Connection $connection)
    {

        $this->modifies = false;

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
        $this->name = $this->referenceSource->getStoredProcedureFinderName();

    }


    /**
     *
     */
    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $this->signature .= "IN " . $column->getSQLParameterName() . " " . $column->getDatabaseType() . ",";
        }

        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }


    protected function buildStatement()
    {
        $where = "";
        foreach ($this->referenceSource->getReferencedColumnList() as $column) {
            $where .= $column->getDatabaseName() . " = " . $column->getSQLParameterName() . " AND ";
        }

        $where = substr($where, 0, -5);
        $tableName = $this->quote($this->tableName);

        $this->statement = "SELECT * FROM $tableName WHERE $where;";

    }


}