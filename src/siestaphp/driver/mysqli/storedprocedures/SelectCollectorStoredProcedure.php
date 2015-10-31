<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use Codeception\Util\Debug;
use siestaphp\datamodel\collector\CollectorGeneratorSource;
use siestaphp\datamodel\collector\NMMapping;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\naming\StoredProcedureNaming;

/**
 * Class SelectStoredProcedure
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class SelectCollectorStoredProcedure extends MySQLStoredProcedureBase
{

    const SQL_JOIN = "SELECT %s.* FROM %s LEFT JOIN %s ON %s WHERE %s;";

    /**
     * @var EntityGeneratorSource
     */
    protected $mappingEntity;

    /**
     * @var EntityGeneratorSource
     */
    protected $foreignEntity;

    /**
     * @param EntityGeneratorSource $source
     * @param NMMapping $nmMapping
     * @param bool $replication
     */
    public function __construct(EntityGeneratorSource $source, NMMapping $nmMapping, $replication)
    {
        parent::__construct($source, $replication);

        $this->mappingEntity = $nmMapping->mappingEntity;

        $this->foreignEntity = $nmMapping->foreignEntity;

        $this->name = $nmMapping->getStoredProcedureName();

        $this->buildElements();
    }

    /**
     * @return void
     */
    protected function buildElements()
    {
        $this->modifies = false;

        $this->determineTableNames();

        $this->buildSignature();

        $this->buildStatement();


    }

    /**
     * @return void
     */
    protected function buildSignature()
    {
        $signatureList = array();
        foreach ($this->foreignEntity->getPrimaryKeyColumns() as $column) {
            $signatureList[] = $this->buildSignatureParameterPart($column);
        }

        $this->signature = $this->buildSignatureSnippet($signatureList);
    }




    /**
     * @return void
     */
    protected function buildStatement()
    {

        $mappingTableName = $this->mappingEntity->getTable();


        $ownTable = $this->entitySource->getTable();

        $onPartList = array();
        foreach ($this->entitySource->getPrimaryKeyColumns() as $pkColumn) {
            $referencedColumn = $this->getReferencedColumnForPKColumn($this->entitySource->getTable(), $pkColumn);

            $onPartList[] = $this->buildTableColumnNameSnippet($ownTable, $pkColumn) . " = " . $this->buildTableColumnNameSnippet($mappingTableName, $referencedColumn);
        }

        $on = implode(" AND ", $onPartList);

        $foreignTable = $this->foreignEntity->getTable();

        $whereList = array();
        foreach($this->foreignEntity->getPrimaryKeyColumns() as $pkColumn) {
            $referencedColumn = $this->getReferencedColumnForPKColumn($foreignTable, $pkColumn);

            $whereList[] = $this->buildTableColumnNameSnippet($mappingTableName, $referencedColumn) . " = " . $pkColumn->getSQLParameterName();
        }

        $where = $this->buildWhereSnippet($whereList);



        $this->statement = sprintf(self::SQL_JOIN, $this->tableName, $this->tableName, $this->quote($mappingTableName), $on, $where);
    }

    /**
     * @param string $tableName
     * @param DatabaseColumn $column
     *
     * @return string
     */
    protected function buildTableColumnNameSnippet($tableName, DatabaseColumn $column)
    {
        return $this->quote($tableName) . "." . $this->quote($column->getDatabaseName());
    }

    /**
     * @param string $tableName
     * @param DatabaseColumn $column
     *
     * @return ReferencedColumnSource
     */
    protected function getReferencedColumnForPKColumn($tableName, DatabaseColumn $column)
    {
        foreach ($this->mappingEntity->getReferenceSourceList() as $reference) {
            if ($reference->getForeignTable() !== $tableName) {
                continue;
            }

            foreach($reference->getReferencedColumnList() as $referencedColumn) {
                if ($referencedColumn->getReferencedDatabaseName() === $column->getDatabaseName()) {
                    return $referencedColumn;
                }
            }
        }
    }

}