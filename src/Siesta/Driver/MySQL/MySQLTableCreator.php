<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Driver\MySQL\MetaData\ConstraintRule;
use Siesta\Model\Attribute;
use Siesta\Model\DelimitAttributeList;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\IndexPart;
use Siesta\Model\Reference;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class MySQLTableCreator
{

    const CREATE_TABLE_SNIPPET = "CREATE TABLE IF NOT EXISTS ";

    const COLUMN_SNIPPET = "%s %s %s";

    const PRIMARY_KEY_SNIPPET = ", PRIMARY KEY (%s)";

    const DEFAULT_CHARSET_SNIPPET = " DEFAULT CHARACTER SET %s ";

    const COLALTE_SNIPPET = " COLLATE ";

    const ENGINE_SNIPPET = " ENGINE = ";

    const FOREIGN_KEY_SNIPPET = ", CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

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
     * @var Entity
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $replication;

    /**
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string[]
     */

    public function buildCreateTable()
    {
        $tableName = $this->entity->getTableName();
        $result = [
            $this->buildCreateTableForTable($tableName, false)
        ];

        if ($this->entity->getIsReplication()) {
            $result[] = $this->buildCreateTableForTable($this->entity->getReplicationTableName(), true);

        }
        return $result;
    }

    /**
     * @param string $tableName
     * @param bool $replication
     *
     * @return string
     */
    public function buildCreateTableForTable($tableName, $replication)
    {

        $sql = self::CREATE_TABLE_SNIPPET . $this->quote($tableName);

        $sql .= " (" . $this->buildColumnSQL();

        $sql .= $this->buildPrimaryKey();

        $sql .= $this->buildIndexList();

        $sql .= $this->buildForeignConstraintList() . ")";

        $sql .= $this->buildEngineDefinition($replication);

        $sql .= $this->buildCollateDefinition();

        $sql .= $this->buildCharsetDefinition();

        return $sql;
    }

    /**
     * @return string
     */
    public function buildCreateDelimitTable()
    {

        $delimiterAttributes = DelimitAttributeList::getDelimitAttributes($this->entity);

        $sql = self::CREATE_TABLE_SNIPPET . $this->quote($this->entity->getDelimitTableName());

        $sql .= "(" . $this->buildColumnSQL($delimiterAttributes);

        $sql .= $this->buildPrimaryKeyForDelimiter($delimiterAttributes) . ")";

        $sql .= $this->buildEngineDefinition();

        $sql .= $this->buildCollateDefinition();

        $sql .= $this->buildCharsetDefinition();

        return $sql;
    }

    /**
     * @param Attribute[] $additionalColumns
     *
     * @return string
     */
    private function buildColumnSQL(array $additionalColumns = []) : string
    {
        $columnList = [];

        // used for delimiter functionality
        foreach ($additionalColumns as $attribute) {
            if (!$attribute->getIsTransient()) {
                $columnList[] = $this->buildColumnSQLSnippet($attribute);
            }
        }

        // iterate all attributes
        foreach ($this->entity->getAttributeList() as $attribute) {
            if (!$attribute->getIsTransient()) {
                $columnList[] = $this->buildColumnSQLSnippet($attribute);
            }
        }

        return implode(", ", $columnList);
    }

    /**
     * @param Attribute $attribute
     *
     * @return string
     */
    private function buildColumnSQLSnippet(Attribute $attribute) : string
    {
        $not = ($attribute->getIsRequired()) ? "NOT NULL" : "NULL";
        $attributeName = $this->quote($attribute->getDBName());
        $attributeType = $attribute->getDbType();
        return sprintf(self::COLUMN_SNIPPET, $attributeName, $attributeType, $not);
    }

    /**
     * @return string
     */
    private function buildPrimaryKey() : string
    {
        $pkColumnList = [];
        foreach ($this->entity->getAttributeList() as $attribute) {
            if (!$attribute->getIsPrimaryKey()) {
                continue;
            }
            $pkColumnList[] = $this->quote($attribute->getDBName());
        }

        if (sizeof($pkColumnList) === 0) {
            return "";
        }

        return sprintf(self::PRIMARY_KEY_SNIPPET, implode(",", $pkColumnList));

    }

    /**
     * @param Attribute[] $delimiterAttributes
     *
     * @return string
     */
    private function buildPrimaryKeyForDelimiter(array $delimiterAttributes)
    {

        $sqlColumnList = [];
        foreach ($delimiterAttributes as $attribute) {
            if ($attribute->getIsPrimaryKey()) {
                $sqlColumnList[] = $this->quote($attribute->getDBName());
            }
        }
        return sprintf(self::PRIMARY_KEY_SNIPPET, implode(",", $sqlColumnList));

    }

    /**
     * @return string
     */
    private function buildIndexList() : string
    {

        $sql = "";
        foreach ($this->entity->getIndexList() as $index) {
            $sql .= "," . $this->buildIndex($index);
        }

        return $sql;
    }

    /**
     * @param Index $index
     *
     * @return string
     */
    private function buildIndex(Index $index)
    {
        // check if unique index or index
        $sql = $index->getIsUnique() ? " UNIQUE INDEX " : " INDEX ";

        // add index name
        $sql .= $this->quote($index->getName());

        // check if an index type has been set
        $indexType = $index->getIndexType();
        if ($indexType !== null) {
            $sql .= " USING " . $indexType;
        }

        // open columns

        $indexPartList = [];
        foreach ($index->getIndexPartList() as $indexPart) {
            $indexPartList[] = $this->buildIndexPart($indexPart);
        }

        $sql .= " (" . implode(", ", $indexPartList) . ")";

        return $sql;
    }

    /**
     * @param IndexPart $indexPart
     *
     * @return string
     */
    private function buildIndexPart(IndexPart $indexPart)
    {
        $sql = $this->quote($indexPart->getColumnName());

        if ($indexPart->getLength()) {
            $sql .= " (" . $indexPart->getLength() . ")";
        }

        $sql .= " " . $indexPart->getSortOrder();

        return $sql;
    }

    /**
     * @return string
     */
    private function buildForeignConstraintList() : string
    {
        $sql = "";
        foreach ($this->entity->getReferenceList() as $reference) {
            $sql .= $this->buildForeignKeyConstraint($reference);
        }
        return $sql;
    }

    /**
     * @param Reference $reference
     *
     * @return string
     */
    private function buildForeignKeyConstraint(Reference $reference) : string
    {
        $columnList = [];
        $foreignColumnList = [];

        foreach ($reference->getReferenceMappingList() as $referenceMapping) {
            $foreignAttribute = $referenceMapping->getForeignAttribute();
            $localAttribute = $referenceMapping->getLocalAttribute();

            $columnList[] = $this->quote($localAttribute->getDBName());
            $foreignColumnList[] = $this->quote($foreignAttribute->getDBName());
        }

        $constraintName = $this->quote($reference->getConstraintName());
        $columnNames = implode(",", $columnList);
        $foreignTable = $this->quote($reference->getForeignTable());
        $foreignColumNames = implode(",", $foreignColumnList);

        $onDelete = ConstraintRule::schemaToMySQL($reference->getOnDelete());
        $onUpdate = ConstraintRule::schemaToMySQL($reference->getOnUpdate());

        return sprintf(self::FOREIGN_KEY_SNIPPET, $constraintName, $columnNames, $foreignTable, $foreignColumNames, $onDelete, $onUpdate);

    }

    /**
     * @param bool $replication
     *
     * @return string
     */
    private function buildEngineDefinition($replication = false)
    {
        if ($replication) {
            return self::ENGINE_SNIPPET . "MEMORY";
        }

        $engine = $this->getDatabaseSpecific(self::MYSQL_ENGINE_ATTRIBUTE);
        if ($engine !== null) {
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
        if ($collate !== null) {
            return self::COLALTE_SNIPPET . $collate;
        }
        return "";
    }

    /**
     * @return string
     */
    private function buildCharsetDefinition() : string
    {
        $charset = $this->getDatabaseSpecific(self::MYSQL_CHARSET_ATTRIBUTE);
        if ($charset !== null) {
            return sprintf(self::DEFAULT_CHARSET_SNIPPET, $charset);
        }
        return "";
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getDatabaseSpecific(string $key)
    {
        $dbSpecific = $this->entity->getDatabaseSpecificAttributeList(MySQLDriver::MYSQL_DRIVER_NAME);
        return ArrayUtil::getFromArray($dbSpecific, $key);
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