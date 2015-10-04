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
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\driver\DriverFactory;

/**
 * Class TableCreator
 * @package siestaphp\driver\mysqli
 */
class TableCreator
{

    const REPLICATION = "replication";

    const MYSQL_ENGINE = "engine";

    const MYSQL_COLLATE = "collate";

    const MYSQL_CHARSET = "charset";

    const REFERENCE_OPTION_CASCADE = "CASCADE";

    const REFERENCE_OPTION_RESTRICT = "RESTRICT";

    const REFERENCE_OPTION_SET_NULL = "SET NULL";

    const REFERENCE_OPTION_NO_ACTION = "NO ACTION";

    /**
     * @param $columnName
     *
     * @return string
     */
    public static function getUniqueIndexName($columnName)
    {
        return $columnName . "_UNIQUE";
    }

    /**
     * @param $columnName
     *
     * @return string
     */
    public static function getIndexName($columnName)
    {
        return $columnName . "_INDEX";
    }

    /**
     * @param string $columnName
     *
     * @return string
     */
    public static function getForeignKeyConstraintName($columnName)
    {
        return strtoupper($columnName) . "_REFERENCE";
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

        $sql = $this->createTable($eds->getTable(), $this->getDatabaseSpecific(self::MYSQL_ENGINE)) . PHP_EOL . PHP_EOL;

        DriverFactory::getDriver()->query($sql);

    }

    /**
     * @param string $tableName
     * @param string $engine
     *
     * @return string
     */
    private function createTable($tableName, $engine)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->quote($tableName);

        $sql .= "(" . $this->createColumnSQL();

        $sql .= $this->buildPrimaryKeySnippet();

        $sql .= $this->buildIndexSnippet();

        $sql .= $this->buildAllForeignKeyConstraint() . ")";

        $sql .= $this->buildEngineSQL($engine);

        $sql .= $this->buildCollateSQL();

        $sql .= $this->buildCharsetSQL();

        return $sql;
    }

    /**
     * @return string
     */
    private function createColumnSQL()
    {
        $sql = "";
        foreach ($this->entityDatabaseSource->getAttributeSourceList() as $attribute) {
            $sql .= $this->buildAttributeSQL($attribute) . ",";
        }
        foreach ($this->entityDatabaseSource->getReferenceSourceList() as $reference) {
            $sql .= $this->buildReferenceSQL($reference) . ",";
        }
        return rtrim($sql, ",");
    }

    /**
     * @param AttributeDatabaseSource $attribute
     *
     * @return string
     */
    private function buildAttributeSQL(AttributeDatabaseSource $attribute)
    {
        return $this->buildColumnSQL($attribute->getDatabaseName(), $attribute->getDatabaseType(), $attribute->isRequired());
    }

    /**
     * @param ReferenceDatabaseSource $reference
     *
     * @return string
     */
    private function buildReferenceSQL(ReferenceDatabaseSource $reference)
    {
        $referenceSQL = "";

        $columnList = $reference->getReferenceColumnList();

        foreach ($columnList as $column) {
            $referenceSQL .= $this->buildColumnSQL($column->getDatabaseName(), $column->getDatabaseType(), $reference->isRequired()) . ",";
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
    private function buildColumnSQL($name, $type, $required)
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
        $sql .=" ( ";

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
            $sql .= $this->buildForeignKeyConstraint($reference);
        }
        return $sql;
    }

    /**
     * @param ReferenceDatabaseSource $rds
     *
     * @return string
     */
    private function buildForeignKeyConstraint(ReferenceDatabaseSource $rds)
    {

        $columnNames = "";
        $referencedNames = "";

        $columnList = $rds->getReferenceColumnList();
        foreach ($columnList as $column) {
            $columnNames .= $this->quote($column->getDatabaseName()) . ",";
            $referencedNames .= $this->quote($column->getReferencedColumnName()) . ",";
        }

        $columnNames = rtrim($columnNames, ",");
        $referencedNames = rtrim($referencedNames, ",");
        $constraintName = self::getForeignKeyConstraintName($rds->getName());

        $sql = ",FOREIGN KEY " . $this->quote($constraintName) . " (" . $columnNames . ")";
        $sql .= " REFERENCES ";
        $sql .= $rds->getReferencedTableName() . "(" . $referencedNames . ")";
        $sql .= " ON DELETE " . $this->getReferenceOption($rds->getOnDelete()) . " ";
        $sql .= " ON UPDATE " . $this->getReferenceOption($rds->getOnUpdate()) . " ";

        return $sql;
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
            case Reference::ON_X_SETNULL:
                return self::REFERENCE_OPTION_SET_NULL;
        }
    }

    /**
     * @param string $engine
     *
     * @return string
     */
    private function buildEngineSQL($engine = null)
    {
        if ($engine) {
            return " ENGINE = $engine";
        }
        return "";
    }

    /**
     * @return string
     */
    private function buildCollateSQL()
    {
        $collate = $this->getDatabaseSpecific(self::MYSQL_COLLATE);
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
        $charset = $this->getDatabaseSpecific(self::MYSQL_CHARSET);
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