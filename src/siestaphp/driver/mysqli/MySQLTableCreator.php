<?php

namespace siestaphp\driver\mysqli;

use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\delimit\DelimitAttribute;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\Reference;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;

/**
 * Class MySQLTableCreator
 * @package siestaphp\driver\mysqli
 */
class MySQLTableCreator
{

    const CREATE_TABLE_SNIPPET = "CREATE TABLE IF NOT EXISTS ";

    const COLUMN_SNIPPET = "%s %s %s NULL";

    const PRIMARY_KEY_SNIPPET = ",PRIMARY KEY (%s)";

    const DEFAULT_CHARSET_SNIPPET = " DEFAULT CHARACTER SET %s ";

    const COLALTE_SNIPPET = " COLLATE ";

    const ENGINE_SNIPPET = " ENGINE = ";

    const FOREIGN_KEY_SNIPPET = ",CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

    const REPLICATION = "replication";

    const MYSQL_ENGINE_ATTRIBUTE = "engine";

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

    /**
     * @var bool
     */
    protected $buildDelimiterTable;

    /**
     * @param EntityGeneratorSource $eds
     */
    public function __construct(EntityGeneratorSource $eds)
    {
        $this->entityGeneratorSource = $eds;

        $this->databaseSpecific = $eds->getDatabaseSpecific(MySQLConnection::NAME);

        $this->replication = $this->getDatabaseSpecificAsBool(self::REPLICATION);

        $this->buildDelimiterTable = false;
    }

    /**
     * @return string
     */

    public function buildCreateTable()
    {
        $this->buildDelimiterTable = false;

        $sql = self::CREATE_TABLE_SNIPPET . $this->quote($this->entityGeneratorSource->getTable());

        $sql .= "(" . $this->buildColumnSQL();

        $sql .= $this->buildPrimaryKey();

        $sql .= $this->buildIndexList();

        $sql .= $this->buildForeignConstraintList() . ")";

        $sql .= $this->buildEngineDefinition();

        $sql .= $this->buildCollateDefinition();

        $sql .= $this->buildCharsetDefinition();

        return $sql;
    }

    /**
     * @return string
     */
    public function buildCreateDelimitTable()
    {

        $delimiterAttributes = DelimitAttribute::getDelimitAttributes();

        $sql = self::CREATE_TABLE_SNIPPET . $this->quote($this->entityGeneratorSource->getDelimitTable());

        $sql .= "(" . $this->buildColumnSQL($delimiterAttributes);

        $sql .= $this->buildPrimaryKeyForDelimiter($delimiterAttributes) . ")";

        $sql .= $this->buildEngineDefinition();

        $sql .= $this->buildCollateDefinition();

        $sql .= $this->buildCharsetDefinition();

        return $sql;
    }

    /**
     * @param AttributeGeneratorSource[] $additionalColumns
     *
     * @return string
     */
    private function buildColumnSQL(array $additionalColumns = array())
    {
        $columnList = array();

        // used for delimiter functionality
        foreach ($additionalColumns as $attribute) {
            if (!$attribute->isTransient()) {
                $columnList[] = $this->buildColumnSQLSnippet($attribute);
            }
        }

        // iterate all attributes
        foreach ($this->entityGeneratorSource->getAttributeGeneratorSourceList() as $attribute) {
            if (!$attribute->isTransient()) {
                $columnList[] = $this->buildColumnSQLSnippet($attribute);
            }
        }

        // iterate all references including referenced columns
        foreach ($this->entityGeneratorSource->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $columnList[] = $this->buildColumnSQLSnippet($column);
            }
        }
        return implode(",", $columnList);
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    private function buildColumnSQLSnippet(DatabaseColumn $column)
    {
        $not = ($column->isRequired()) ? "NOT" : "";
        return sprintf(self::COLUMN_SNIPPET, $this->quote($column->getDatabaseName()), $column->getDatabaseType(), $not);
    }

    /**
     * @return string
     */
    private function buildPrimaryKey()
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
     * @param AttributeGeneratorSource[] $delimiterAttributes
     *
     * @return string
     */
    private function buildPrimaryKeyForDelimiter(array $delimiterAttributes)
    {

        $sqlColumnList = array();
        foreach ($delimiterAttributes as $column) {
            if ($column->isPrimaryKey()) {
                $sqlColumnList[] = $this->quote($column->getDatabaseName());
            }
        }
        return sprintf(self::PRIMARY_KEY_SNIPPET, implode(",", $sqlColumnList));

    }

    /**
     * @return string
     */
    private function buildIndexList()
    {

        $sql = "";
        foreach ($this->entityGeneratorSource->getIndexSourceList() as $indexSource) {
            $sql .= "," . $this->buildIndex($indexSource);
        }

        return $sql;
    }

    /**
     * @param IndexSource $indexSource
     *
     * @return string
     */
    private function buildIndex(IndexSource $indexSource)
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
     * @return string
     */
    private function buildForeignConstraintList()
    {
        $sql = "";
        foreach ($this->entityGeneratorSource->getReferenceGeneratorSourceList() as $reference) {
            $sql .= $this->buildForeignKeyConstraint($reference);
        }
        return $sql;
    }

    /**
     * @param ReferenceGeneratorSource $rds
     *
     * @return string
     */
    private function buildForeignKeyConstraint(ReferenceGeneratorSource $rds)
    {
        $columnList = array();
        $foreignColumnList = array();

        foreach ($rds->getReferencedColumnList() as $column) {
            $columnList[] = $this->quote($column->getDatabaseName());
            $foreignColumnList[] = $this->quote($column->getReferencedDatabaseName());
        }

        $constraintName = $rds->getConstraintName();
        $columnNames = implode(",", $columnList);
        $tableName = $rds->getReferencedTableName();
        $referencedColumnNames = implode(",", $foreignColumnList);
        $onDelete = $this->getReferenceOption($rds->getOnDelete());
        $onUpdate = $this->getReferenceOption($rds->getOnUpdate());

        return sprintf(self::FOREIGN_KEY_SNIPPET, $constraintName, $columnNames, $tableName, $referencedColumnNames, $onDelete, $onUpdate);

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
    private function buildEngineDefinition()
    {
        $engine = $this->getDatabaseSpecific(self::MYSQL_ENGINE_ATTRIBUTE);
        if ($engine) {
            return self::ENGINE_SNIPPET . $engine;
        }
        return "";
    }

    /**
     * @return string
     */
    private function buildCollateDefinition()
    {
        $collate = $this->getDatabaseSpecific(self::MYSQL_COLLATE_ATTRIBUTE);
        if ($collate) {
            return self::COLALTE_SNIPPET . $collate;
        }
        return "";
    }

    /**
     * @return string
     */
    private function buildCharsetDefinition()
    {
        $charset = $this->getDatabaseSpecific(self::MYSQL_CHARSET_ATTRIBUTE);
        if ($charset) {
            return sprintf(self::DEFAULT_CHARSET_SNIPPET, $charset);
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