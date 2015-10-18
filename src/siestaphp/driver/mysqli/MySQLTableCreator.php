<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 18:07
 */

namespace siestaphp\driver\mysqli;

use Codeception\Util\Debug;
use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\index\IndexGeneratorSource;
use siestaphp\datamodel\index\IndexPartGeneratorSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\driver\ConnectionFactory;

/**
 * Class MySQLTableCreator
 * @package siestaphp\driver\mysqli
 */
class MySQLTableCreator
{

    const CREATE_TABLE = "CREATE TABLE IF NOT EXISTS ";

    const PRIMARY_KEY_SNIPPET = ",PRIMARY KEY (%s)";

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
     * @var EntityGeneratorSource
     */
    protected $entityGeneratorSource;

    /**
     * @var DatabaseSpecificSource
     */
    protected $databaseSpecific;

    /**
     * @var bool
     */
    protected $replication;

    protected $tableName;

    /**
     * @param EntityGeneratorSource $eds
     *
     * @return string
     */

    public function setupTable(EntityGeneratorSource $eds)
    {
        $this->entityGeneratorSource = $eds;

        $this->databaseSpecific = $eds->getDatabaseSpecific(MySQLConnection::NAME);

        $this->replication = $this->getDatabaseSpecificAsBool(self::REPLICATION);

        $this->tableName = $eds->getTable();

        $sql = $this->buildTableCreateStatement($eds->getTable());

        return $sql;

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
        $columnList = array();
        foreach ($this->entityGeneratorSource->getAttributeSourceList() as $attribute) {
            if (!$attribute->isTransient()) {
                $columnList[] = $this->buildAttributeColumnSQL($attribute);
            }
        }
        foreach ($this->entityGeneratorSource->getReferenceGeneratorSourceList() as $reference) {
            $columnList[] = $this->buildReferenceColumnSQL($reference);
        }
        return implode(",", $columnList);
    }

    /**
     * @param AttributeSource $attribute
     *
     * @return string
     */
    private function buildAttributeColumnSQL(AttributeSource $attribute)
    {
        return $this->buildColumnSQLSnippet($attribute->getDatabaseName(), $attribute->getDatabaseType(), $attribute->isRequired());
    }

    /**
     * @param ReferenceGeneratorSource $reference
     *
     * @return string
     */
    private function buildReferenceColumnSQL(ReferenceGeneratorSource $reference)
    {
        $columnList = array();
        foreach ($reference->getReferencedColumnList() as $column) {
            $columnList[] = $this->buildColumnSQLSnippet($column->getDatabaseName(), $column->getDatabaseType(), $reference->isRequired());
        }

        return implode(",", $columnList);
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
        $pkColumnList = $this->entityGeneratorSource->getPrimaryKeyColumns();
        if (sizeof($pkColumnList) === 0) {
            return "";
        }

        $sqlColumnList = array();
        foreach ($pkColumnList as $column) {
            $sqlColumnList[] = $this->quote($column->getDatabaseName());
        }
        return sprintf(self::PRIMARY_KEY_SNIPPET, implode(",", $sqlColumnList));

    }

    /**
     * @return string
     */
    private function buildIndexSnippet()
    {

        $sql = "";
        foreach ($this->entityGeneratorSource->getIndexGeneratorSourceList() as $indexSource) {
            $sql .= "," . $this->buildIndex($indexSource);
        }

        return $sql;
    }

    /**
     * @param IndexGeneratorSource $indexSource
     *
     * @return string
     */
    private function buildIndex(IndexGeneratorSource $indexSource)
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

        foreach ($indexSource->getIndexPartGeneratorSourceList() as $indexPartSource) {
            $sql .= $this->buildIndexPart($indexPartSource) . ",";
        }

        $sql = rtrim($sql, ",") . ")";

        return $sql;
    }

    /**
     * @param IndexPartGeneratorSource $indexPartSource
     *
     * @return string
     */
    private function buildIndexPart(IndexPartGeneratorSource $indexPartSource)
    {
        $sql = "";
        foreach ($indexPartSource->getIndexColumnList() as $column) {
            $sql .= $this->quote($column->getDatabaseName());
            if ($indexPartSource->getLength()) {
                $sql .= " (" . $indexPartSource->getLength() . ")";
            }

            $sql .= " " . $indexPartSource->getSortOrder();
        }

        return $sql;
    }

    /**
     * @return string
     */
    private function buildAllForeignKeyConstraint()
    {
        $sql = "";
        foreach ($this->entityGeneratorSource->getReferenceGeneratorSourceList() as $reference) {
            $sql .= $this->buildForeignKeyConstraintSQL($reference);
        }
        return $sql;
    }

    /**
     * @param ReferenceGeneratorSource $rds
     *
     * @return string
     */
    private function buildForeignKeyConstraintSQL(ReferenceGeneratorSource $rds)
    {
        $columnList = array();
        $foreignColumnList = array();

        foreach ($rds->getReferencedColumnList() as $column) {
            $columnList[] = $this->quote($column->getDatabaseName());
            $foreignColumnList[] = $this->quote($column->getReferencedDatabaseName());
        }

        $columnNames = implode(",", $columnList);
        $referencedColumnNames = implode(",", $foreignColumnList);

        $constraintName = $rds->getConstraintName();
        $onDelete = $this->getReferenceOption($rds->getOnDelete());
        $onUpdate = $this->getReferenceOption($rds->getOnUpdate());

        $sql = $this->buildConstraintSnippet($constraintName, $columnNames, $rds->getReferencedTableName(), $referencedColumnNames, $onDelete, $onUpdate);

        return $sql;
    }

    /**
     * @param string $constraintName
     * @param string $columnNames
     * @param string $tableName
     * @param string $referencedNames
     * @param string $onDelete
     * @param string $onSetNull
     *
     * @return string
     */
    private function buildConstraintSnippet($constraintName, $columnNames, $tableName, $referencedNames, $onDelete, $onSetNull)
    {
        $constraintName = $this->quote($constraintName);
        $tableName = $this->quote($tableName);

        $constraintSQL = ",CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

        return sprintf($constraintSQL, $constraintName, $columnNames, $tableName, $referencedNames, $onDelete, $onSetNull);
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
        return MySQLConnection::quote($name);
    }

}