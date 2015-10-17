<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 13.10.15
 * Time: 20:14
 */

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntitySource;
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

    const ADD_FOREIGN_KEY = "ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

    const DROP_FOREIGN_KEY = "ALTER TABLE %s DROP FOREIGN KEY %s";

    /**
     * @param EntitySource $entitySource
     *
     * @return string
     */
    public function getDropTableStatement(EntitySource $entitySource)
    {
        return sprintf(self::DROP_TABLE, ColumnMigrator::TABLE_PLACE_HOLDER);
    }

    /**
     * @param string $columnName
     *
     * @return string
     */
    public function createDropColumnStatement($columnName)
    {
        return sprintf(self::DROP_COLUMN, ColumnMigrator::TABLE_PLACE_HOLDER, $this->quote($columnName));
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createAddColumnStatement(DatabaseColumn $column)
    {
        $nullHandling = ($column->isRequired()) ? "NOT NULL" : "NULL";
        return sprintf(self::ADD_COLUMN, ColumnMigrator::TABLE_PLACE_HOLDER, $this->quote($column->getDatabaseName()), $column->getDatabaseType(), $nullHandling);
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createModifiyColumnStatement(DatabaseColumn $column)
    {
        $nullHandling = ($column->isRequired()) ? "NOT NULL" : "NULL";
        return sprintf(self::MODIFY_COLUMN, ColumnMigrator::TABLE_PLACE_HOLDER, $this->quote($column->getDatabaseName()), $column->getDatabaseType(), $nullHandling);
    }

    /**
     * @param ReferenceGeneratorSource $reference
     *
     * @return string
     */
    public function createAddForeignKeyStatement(ReferenceGeneratorSource $reference)
    {
        // TODO: Implement createAddForeignKeyStatement() method.
    }

    /**
     * @param ReferenceSource $reference
     *
     * @return string
     */
    public function createDropForeignKeyStatemtn(ReferenceSource $reference)
    {
        // TODO: Implement createDropForeignKeyStatemtn() method.
    }

    public function createAddIndexStatement()
    {
        // TODO: Implement createAddIndexStatement() method.
    }

    public function createDropIndexStatement()
    {
        // TODO: Implement createDropIndexStatement() method.
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