<?php

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\index\IndexGeneratorSource;
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\driver\ColumnMigrator;

/**
 * Class MysqlColumnMigrator
 * @package siestaphp\driver\mysqli
 */
class MysqlColumnMigrator implements ColumnMigrator

{
    const ADD_COLUMN = "ALTER TABLE %s ADD %s %s %s";

    const MODIFY_COLUMN = "ALTER TABLE %s MODIFY %s %s %s";

    const DROP_COLUMN = "ALTER TABLE %s DROP COLUMN %s";

    const DROP_INDEX = "ALTER TABLE %s DROP INDEX %s";

    const ADD_INDEX = "ALTER TABLE %s ADD INDEX %s";

    const DROP_TABLE = "DROP TABLE IF EXISTS %s";

    const DROP_PRIMARY_KEY = "ALTER TABLE %s DROP PRIMARY KEY";

    const MODIFY_PRIMARY_KEY = "ALTER TABLE %s DROP PRIMARY KEY, ADD PRIMARY KEY (%s);";

    const ADD_FOREIGN_KEY = "ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

    const DROP_FOREIGN_KEY = "ALTER TABLE %s DROP FOREIGN KEY %s";

    protected $tableName;

    /**
     *
     */
    public function __construct()
    {
        $this->tableName = $this->quote(ColumnMigrator::TABLE_PLACE_HOLDER);
    }

    /**
     * @param EntitySource $entitySource
     *
     * @return string
     */
    public function getDropTableStatement(EntitySource $entitySource)
    {
        return sprintf(self::DROP_TABLE, $this->tableName);
    }

    /**
     * @param EntitySource $asIs
     * @param EntityGeneratorSource $toBe
     *
     * @return string[]
     */
    public function getModifyPrimaryKeyStatement(EntitySource $asIs, EntityGeneratorSource $toBe)
    {

        $pkList = [];

        // assemble PK list
        foreach ($toBe->getAttributeSourceList() as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $pkList[] = $this->quote($attribute->getDatabaseName());
            }
        }

        foreach ($toBe->getReferenceSourceList() as $reference) {
            if ($reference->isPrimaryKey()) {
                foreach ($reference->getReferencedColumnList() as $column) {
                    $pkList[] = $this->quote($column->getDatabaseName());
                }
            }
        }

        if (sizeof($pkList) === 0) {
            return [sprintf(self::DROP_PRIMARY_KEY, $this->tableName)];
        }

        $pkColumns = implode(",", $pkList);

        return [sprintf(self::MODIFY_PRIMARY_KEY, $this->tableName, $pkColumns)];
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createDropColumnStatement(DatabaseColumn $column)
    {
        return sprintf(self::DROP_COLUMN, $this->tableName, $this->quote($column->getDatabaseName()));
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createAddColumnStatement(DatabaseColumn $column)
    {
        $nullHandling = ($column->isRequired()) ? "NOT NULL" : "NULL";
        return sprintf(self::ADD_COLUMN, $this->tableName, $this->quote($column->getDatabaseName()), $column->getDatabaseType(), $nullHandling);
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createModifiyColumnStatement(DatabaseColumn $column)
    {
        $nullHandling = ($column->isRequired()) ? "NOT NULL" : "NULL";
        return sprintf(self::MODIFY_COLUMN, $this->tableName, $this->quote($column->getDatabaseName()), $column->getDatabaseType(), $nullHandling);
    }

    /**
     * @param ReferenceGeneratorSource $reference
     *
     * @return string
     */
    public function createAddForeignKeyStatement(ReferenceGeneratorSource $reference)
    {

        $constraintName = $reference->getConstraintName();
        $foreignTable = $reference->getForeignTable();
        $onDelete = $reference->getOnDelete();
        $onUpdate = $reference->getOnUpdate();

        $columnList = [];
        $foreignColumList = [];
        foreach ($reference->getReferencedColumnList() as $column) {
            $columnList[] = $this->quote($column->getDatabaseName());
            $foreignColumList[] = $this->quote($column->getReferencedDatabaseName());
        }

        $colum = implode(",", $columnList);
        $foreignColum = implode(",", $foreignColumList);

        return sprintf(self::ADD_FOREIGN_KEY, $this->tableName, $constraintName, $colum, $foreignTable, $foreignColum, $onDelete, $onUpdate);

    }

    /**
     * @param ReferenceSource $reference
     *
     * @return string
     */
    public function createDropForeignKeyStatement(ReferenceSource $reference)
    {
        return sprintf(self::DROP_FOREIGN_KEY, $this->tableName, $reference->getConstraintName());
    }

    /**
     * @param IndexSource $indexSource
     *
     * @return string
     */
    public function createAddIndexStatement(IndexSource $indexSource)
    {
        $sql = "ALTER TABLE " . $this->tableName . " ADD ";
        // check if unique index or index
        $sql .= $indexSource->isUnique() ? "UNIQUE INDEX " : " INDEX ";

        // add index name
        $sql .= $this->quote($indexSource->getName());

        // check if an index type has been set
        if ($indexSource->getType()) {
            $sql .= " USING " . $indexSource->getType();
        }

        // open columns
        $sql .= " ( ";

        foreach ($indexSource->getIndexPartSourceList() as $indexPartSource) {
            $sql .= $this->buildIndexPart($indexPartSource) . ",";
        }

        $sql = rtrim($sql, ",") . ")";

        return $sql;

    }

    /**
     * @param IndexPartSource $indexPartSource
     *
     * @return string
     */
    private function buildIndexPart(IndexPartSource $indexPartSource)
    {

        $sql = $this->quote($indexPartSource->getColumnName());
        if ($indexPartSource->getLength()) {
            $sql .= " (" . $indexPartSource->getLength() . ")";
        }

        $sql .= " " . $indexPartSource->getSortOrder();

        return $sql;
    }

    /**
     * @param IndexSource $indexSource
     *
     * @return string
     */
    public function createDropIndexStatement(IndexSource $indexSource)
    {
        return sprintf(self::DROP_INDEX, $this->tableName, $indexSource->getName());
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function quote($name)
    {
        return MySQLDriver::quote($name);
    }

}