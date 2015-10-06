<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 18:07
 */

namespace siestaphp\driver\mysqli;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\AttributeDatabaseSource;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\entity\EntityDatabaseSource;
use siestaphp\datamodel\index\IndexDatabaseSource;
use siestaphp\datamodel\index\IndexPartDatabaseSource;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\driver\DriverFactory;

/**
 * Class TableCreator
 * @package siestaphp\driver\mysqli
 */
class TableCreator
{

    const CREATE_TABLE = "CREATE TABLE IF NOT EXISTS ";

    const FOREIGN_KEY_CONSTRAINT = ",CONSTRAINT %s FOREIGN KEY %s (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

    const REPLICATION = "replication";

    const MYSQL_ENGINE_ATTRIBUTE = "engine";

    const MYSQL_ENGINE = " ENGINE = ";

    const MYSQL_COLLATE_ATTRIBUTE = "collate";

    const MYSQL_CHARSET_ATTRIBUTE = "charset";

    const REFERENCE_OPTION_CASCADE = "CASCADE";

    const REFERENCE_OPTION_RESTRICT = "RESTRICT";

    const REFERENCE_OPTION_SET_NULL = "SET NULL";

    const REFERENCE_OPTION_NO_ACTION = "NO ACTION";

    const UNIQUE_SUFFIX = "_UNIQUE";

    const INDEX_SUFFIX = "_INDEX";

    const FOREIGN_KEY_SUFFIX = "_FOREIGN_KEY";

    const FOREIGN_KEY_INDEX_SUFFIX = "_FK_INDEX";

    /**
     * @param $columnName
     *
     * @return string
     */
    public static function getUniqueIndexName($columnName)
    {
        return $columnName . self::UNIQUE_SUFFIX;
    }

    /**
     * @param $columnName
     *
     * @return string
     */
    public static function getIndexName($columnName)
    {
        return $columnName . self::INDEX_SUFFIX;
    }

    /**
     * @param string $columnName
     *
     * @return string
     */
    public static function getForeignKeyConstraintName($columnName)
    {
        return strtoupper($columnName) . self::FOREIGN_KEY_SUFFIX;
    }

    /**
     * @param string $columnName
     *
     * @return string
     */
    public static function getForeignKeyConstraintIndexName($columnName)
    {
        return strtoupper($columnName) . self::FOREIGN_KEY_INDEX_SUFFIX;
    }

    /**
     * @var EntityDatabaseSource
     */
    protected $entityDatabaseSource;

    /**
     * @var DatabaseSpecificSource
     */
    protected $databaseSpecific;

    /**
     * @var bool
     */
    protected $replication;

    /**
     * @param EntityDatabaseSource $eds
     */
    public function setupTable(EntityDatabaseSource $eds)
    {
        $this->entityDatabaseSource = $eds;

        $this->databaseSpecific = $eds->getDatabaseSpecific(MySQLDriver::NAME);

        $this->replication = $this->getDatabaseSpecificAsBool(self::REPLICATION);

        $sql = $this->buildTableCreateStatement($eds->getTable());

        DriverFactory::getDriver()->query($sql);

    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    private function buildTableCreateStatement($tableName)
    {
        $sql = self::CREATE_TABLE . $this->quote($tableName);

        $sql .= "(" . $this->buildColumnSQL();

        $sql .= $this->buildPrimaryKeySnippet();

        $sql .= $this->buildIndexSnippet();

        $sql .= $this->buildAllForeignKeyConstraint() . ")";

        $sql .= $this->buildEngineSQL();

        $sql .= $this->buildCollateSQL();

        $sql .= $this->buildCharsetSQL();

        return $sql;
    }

    /**
     * @return string
     */
    private function buildColumnSQL()
    {
        $sql = "";
        foreach ($this->entityDatabaseSource->getAttributeSourceList() as $attribute) {
            $sql .= $this->buildAttributeColumnSQL($attribute) . ",";
        }
        foreach ($this->entityDatabaseSource->getReferenceSourceList() as $reference) {
            $sql .= $this->buildReferenceColumnSQL($reference) . ",";
        }
        return rtrim($sql, ",");
    }

    /**
     * @param AttributeDatabaseSource $attribute
     *
     * @return string
     */
    private function buildAttributeColumnSQL(AttributeDatabaseSource $attribute)
    {
        return $this->buildColumnSQLSnippet($attribute->getDatabaseName(), $attribute->getDatabaseType(), $attribute->isRequired());
    }

    /**
     * @param ReferenceDatabaseSource $reference
     *
     * @return string
     */
    private function buildReferenceColumnSQL(ReferenceDatabaseSource $reference)
    {
        $referenceSQL = "";

        $columnList = $reference->getReferenceColumnList();

        foreach ($columnList as $column) {
            $referenceSQL .= $this->buildColumnSQLSnippet($column->getDatabaseName(), $column->getDatabaseType(), $reference->isRequired()) . ",";
        }

        return rtrim($referenceSQL, ",");
    }

    /**
     * @param string $name
     * @param string $type
     * @param bool $required
     *
     * @return string
     */
    private function buildColumnSQLSnippet($name, $type, $required)
    {
        $sql = $this->quote($name) . " " . $type;
        if ($required) {
            $sql .= " NOT NULL";
        } else {
            $sql .= " NULL";
        }
        return $sql;
    }

    /**
     * @return string
     */
    private function buildPrimaryKeySnippet()
    {
        $sql = " ,PRIMARY KEY (";

        foreach ($this->entityDatabaseSource->getAttributeSourceList() as $attribute) {
            if ($attribute->isPrimaryKey()) {
                $sql .= $this->quote($attribute->getDatabaseName()) . ",";
            }
        }
        $sql = rtrim($sql, ",");

        return $sql . ")";
    }

    /**
     * @return string
     */
    private function buildIndexSnippet()
    {

        $sql = "";
        foreach ($this->entityDatabaseSource->getIndexSourceList() as $indexSource) {
            $sql .= "," . $this->buildIndex($indexSource);
        }

        return $sql;
    }

    /**
     * @param IndexDatabaseSource $indexSource
     *
     * @return string
     */
    private function buildIndex(IndexDatabaseSource $indexSource)
    {
        // check if unique index or index
        $sql = $indexSource->isUnique() ? " UNIQUE INDEX " : " INDEX ";

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
     * @param IndexPartDatabaseSource $indexPartSource
     *
     * @return string
     */
    private function buildIndexPart(IndexPartDatabaseSource $indexPartSource)
    {
        $sql = "";
        foreach ($indexPartSource->getIndexColumnList() as $column) {
            $sql .= $this->quote($column->getDatabaseName());
            if ($indexPartSource->getLength()) {
                $sql .= " (" . $indexPartSource->getLength() . ") ";
            }

            $sql .= $indexPartSource->getSortOrder() . ",";
        }

        return $sql;
    }

    /**
     * @return string
     */
    private function buildAllForeignKeyConstraint()
    {
        $sql = "";
        foreach ($this->entityDatabaseSource->getReferenceSourceList() as $reference) {
            $sql .= $this->buildForeignKeyConstraintSQL($reference);
        }
        return $sql;
    }

    /**
     * @param ReferenceDatabaseSource $rds
     *
     * @return string
     */
    private function buildForeignKeyConstraintSQL(ReferenceDatabaseSource $rds)
    {

        $columnNames = "";
        $referencedColumnNames = "";

        $columnList = $rds->getReferenceColumnList();
        foreach ($columnList as $column) {
            $columnNames .= $this->quote($column->getDatabaseName()) . ",";
            $referencedColumnNames .= $this->quote($column->getReferencedColumnName()) . ",";
        }

        $columnNames = rtrim($columnNames, ",");
        $referencedColumnNames = rtrim($referencedColumnNames, ",");

        $constraintName = self::getForeignKeyConstraintName($rds->getName());
        $constraintIndexName = self::getForeignKeyConstraintIndexName($rds->getName());
        $onDelete = $this->getReferenceOption($rds->getOnDelete());
        $onSetNull = $this->getReferenceOption($rds->getOnUpdate());

        $sql = $this->buildConstraintSnippet($constraintName, $constraintIndexName, $columnNames, $rds->getReferencedTableName(), $referencedColumnNames, $onDelete, $onSetNull);

        return $sql;
    }

    /**
     * @param string $constraintName
     * @param string $constraintIndexName
     * @param string $columnNames
     * @param string $tableName
     * @param string $referencedNames
     * @param string $onDelete
     * @param string $onSetNull
     *
     * @return string
     */
    private function buildConstraintSnippet($constraintName, $constraintIndexName, $columnNames, $tableName, $referencedNames, $onDelete, $onSetNull)
    {
        $constraintName = $this->quote($constraintName);
        $constraintIndexName = $this->quote($constraintIndexName);
        $tableName = $this->quote($tableName);
        return sprintf(self::FOREIGN_KEY_CONSTRAINT, $constraintName, $constraintIndexName, $columnNames, $tableName, $referencedNames, $onDelete, $onSetNull);
    }

    /**
     * @param string $option
     *
     * @return string
     */
    private function getReferenceOption($option)
    {
        switch ($option) {
            case Reference::ON_X_CASCADE:
                return self::REFERENCE_OPTION_CASCADE;
            case Reference::ON_X_NONE:
                return self::REFERENCE_OPTION_NO_ACTION;
            case Reference::ON_X_RESTRICT:
                return self::REFERENCE_OPTION_RESTRICT;
            case Reference::ON_X_SET_NULL:
                return self::REFERENCE_OPTION_SET_NULL;
        }
    }

    /**
     * @return string
     */
    private function buildEngineSQL()
    {
        $engine = $this->getDatabaseSpecific(self::MYSQL_ENGINE_ATTRIBUTE);
        if ($engine) {
            return self::MYSQL_ENGINE . $engine;
        }
        return "";
    }

    /**
     * @return string
     */
    private function buildCollateSQL()
    {
        $collate = $this->getDatabaseSpecific(self::MYSQL_COLLATE_ATTRIBUTE);
        if ($collate) {
            return " COLLATE " . $collate;
        }
        return "";
    }

    /**
     * @return string
     */
    private function buildCharsetSQL()
    {
        $charset = $this->getDatabaseSpecific(self::MYSQL_CHARSET_ATTRIBUTE);
        if ($charset) {
            return " DEFAULT CHARACTER SET " . $charset;
        }
        return "";
    }

    /**
     * @param string $key
     *
     * @return string string
     */
    private function getDatabaseSpecific($key)
    {
        if (!$this->databaseSpecific) {
            return null;
        }
        return $this->databaseSpecific->getAttribute($key);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    private function getDatabaseSpecificAsBool($key)
    {
        if (!$this->databaseSpecific) {
            return null;
        }
        return $this->databaseSpecific->getAttributeAsBool($key);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function quote($name)
    {
        return MySQLDriver::quote($name);
    }

}