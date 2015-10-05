<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 30.06.15
 * Time: 19:08
 */

namespace siestaphp\driver\mysqli\storedprocedures;


use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\driver\Driver;
use siestaphp\driver\mysqli\replication\Replication;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class InsertStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class InsertStoredProcedure extends StoredProcedureBase
{

    const INSERT_STATEMENT = "INSERT INTO %s ( %s ) VALUES ( %s );";


    /**
     * @param EntityDatabaseSource $eds
     * @param $replication
     */
    public function __construct(EntityDatabaseSource $eds, $replication)
    {
        parent::__construct($eds, $replication);
    }

    /**
     * @param Driver $driver
     */
    public function createProcedure(Driver $driver)
    {
        $this->modifies = true;

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
        $this->name = StoredProcedureNaming::getSPInsertName($this->entityDatabaseSource->getTable());
    }

    protected function buildSignature()
    {
        $this->signature = "(";

        foreach ($this->entityDatabaseSource->getReferenceDatabaseSourceList() as $reference) {
            foreach ($reference->getReferenceColumnList() as $column) {
                $parameterName = $column->getSQLParameterName();
                $this->signature .= "IN $parameterName " . $column->getDatabaseType() . ",";
            }


        }

        foreach ($this->entityDatabaseSource->getAttributeDatabaseSourceList() as $attribute) {
            $parameterName = $attribute->getSQLParameterName();
            $this->signature .= "IN $parameterName " . $attribute->getDatabaseType() . ",";
        }
        $this->signature = rtrim($this->signature, ",");
        $this->signature .= ")";
    }

    protected function buildStatement()
    {
        $this->statement = $this->buildInsertSQL($this->entityDatabaseSource->getTable());

        if ($this->replication) {
            $table = Replication::getReplicationTableName($this->entityDatabaseSource->getTable());
            $this->statement .= $this->buildInsertSQL($table);
        }
    }


    /**
     * builds the insert statement
     *
     * @param string $tableName
     * @return string
     */
    protected function buildInsertSQL($tableName)
    {
        // initialize column and value list
        $columnList = "";
        $valueList = "";

        // iterate referenced columns first
        foreach ($this->entityDatabaseSource->getReferenceDatabaseSourceList() as $reference) {
            foreach ($reference->getReferenceColumnList() as $column) {
                $columnList .= $this->quote($column->getDatabaseName()) . ",";
                $valueList .= $column->getSQLParameterName() . ",";
            }
        }

        // iterate attributes
        foreach ($this->entityDatabaseSource->getAttributeDatabaseSourceList() as $attribute) {
            $columnList .= $this->quote($attribute->getDatabaseName()) . ",";
            $valueList .= $attribute->getSQLParameterName() . ",";
        }

        // finalize components
        $tableName = $this->quote($tableName);
        $columnList = rtrim($columnList, ",");
        $valueList = rtrim($valueList, ",");

        // done
        return sprintf(self::INSERT_STATEMENT, $tableName, $columnList, $valueList);
    }


}