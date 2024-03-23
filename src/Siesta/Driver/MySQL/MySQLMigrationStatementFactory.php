<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\Attribute;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\IndexPart;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
class MySQLMigrationStatementFactory implements MigrationStatementFactory
{
    const DROP_PRIMARY_KEY = "ALTER TABLE %s DROP PRIMARY KEY";

    const MODIFY_PRIMARY_KEY = "ALTER TABLE %s DROP PRIMARY KEY, ADD PRIMARY KEY (%s);";

    const DROP_TABLE = "DROP TABLE IF EXISTS %s";

    const DROP_COLUMN = "ALTER TABLE %s DROP COLUMN %s";

    const ADD_COLUMN = "ALTER TABLE %s ADD %s %s %s";

    const MODIFY_COLUMN = "ALTER TABLE %s MODIFY %s %s %s";

    const DROP_INDEX = "ALTER TABLE %s DROP INDEX %s";

    const ADD_FOREIGN_KEY = "ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) ON DELETE %s ON UPDATE %s";

    const DROP_FOREIGN_KEY = "ALTER TABLE %s DROP FOREIGN KEY %s";

    protected $tableName;

    /**
     *
     */
    public function __construct()
    {
        $this->tableName = $this->quote(MigrationStatementFactory::TABLE_PLACE_HOLDER);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function quote(string $name): string
    {
        return MySQLDriver::quote($name);
    }

    /**
     * @param TableMetaData $table
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getModifyPrimaryKeyStatement(TableMetaData $table, Entity $entity) : array
    {
        $pkList = [];

        // assemble PK list
        foreach ($entity->getPrimaryKeyAttributeList() as $attribute) {
            $pkList[] = $this->quote($attribute->getDBName());
        }

        if (sizeof($pkList) === 0) {
            return [sprintf(self::DROP_PRIMARY_KEY, $this->tableName)];
        }

        $pkColumns = implode(",", $pkList);

        $statement = sprintf(self::MODIFY_PRIMARY_KEY, $this->tableName, $pkColumns);
        return [$statement];

    }

    /**
     * @param TableMetaData $table
     *
     * @return string[]
     */
    public function getDropTableStatement(TableMetaData $table) : array
    {
        $statement = sprintf(self::DROP_TABLE, $this->tableName);
        return [$statement];
    }

    /**
     * @param ColumnMetaData $column
     *
     * @return string[]
     */
    public function createDropColumnStatement(ColumnMetaData $column): array
    {
        $statement = sprintf(self::DROP_COLUMN, $this->tableName, $this->quote($column->getDBName()));
        return [$statement];
    }

    /**
     * @param Attribute $attribute
     *
     * @return string[]
     */
    public function createAddColumnStatement(Attribute $attribute): array
    {
        $nullHandling = ($attribute->getIsRequired()) ? "NOT NULL" : "NULL";
        $statement = sprintf(self::ADD_COLUMN, $this->tableName, $this->quote($attribute->getDBName()), $attribute->getDbType(), $nullHandling);
        return [$statement];
    }

    /**
     * @param Attribute $attribute
     *
     * @return string[]
     */
    public function createModifyColumnStatement(Attribute $attribute): array
    {
        $nullHandling = ($attribute->getIsRequired()) ? "NOT NULL" : "NULL";
        $statement = sprintf(self::MODIFY_COLUMN, $this->tableName, $this->quote($attribute->getDBName()), $attribute->getDbType(), $nullHandling);
        return [$statement];
    }

    /**
     * @param Reference $reference
     *
     * @return string[]
     */
    public function createAddReferenceStatement(Reference $reference): array
    {
        $constraintName = $this->quote($reference->getConstraintName());
        $foreignTable = $this->quote($reference->getForeignTable());
        $onDelete = $reference->getOnDelete();
        $onUpdate = $reference->getOnUpdate();

        $localColumnList = [];
        $foreignColumnList = [];
        foreach ($reference->getReferenceMappingList() as $mapping) {
            $localColumnList[] = $this->quote($mapping->getLocalColumnName());
            $foreignColumnList[] = $this->quote($mapping->getForeignColumnName());
        }

        $localColumn = implode(",", $localColumnList);
        $foreignColumn = implode(",", $foreignColumnList);

        $statement = sprintf(self::ADD_FOREIGN_KEY, $this->tableName, $constraintName, $localColumn, $foreignTable, $foreignColumn, $onDelete, $onUpdate);
        return [$statement];
    }

    /**
     * @param ConstraintMetaData $constraint
     *
     * @return string[]
     */
    public function createDropConstraintStatement(ConstraintMetaData $constraint): array
    {
        $statement = sprintf(self::DROP_FOREIGN_KEY, $this->tableName, $constraint->getConstraintName());
        return [$statement];
    }

    const ADD_INDEX = "ALTER TABLE %s ADD %s INDEX %s %s (%s)";

    /**
     * @param Index $index
     *
     * @return string[]
     */
    public function createAddIndexStatement(Index $index): array
    {

        $unique = $index->getIsUnique() ? "UNIQUE" : "";
        $using = $index->getIndexType() ? "USING " . $index->getIndexType() : "";
        $indexName = $this->quote($index->getName());

        $indexPartList = [];
        foreach ($index->getIndexPartList() as $indexPart) {
            $indexPartList[] = $this->buildIndexPart($indexPart);
        }
        $indexPart = implode(", ", $indexPartList);

        $statement = sprintf(self::ADD_INDEX, $this->tableName, $unique, $indexName, $using, $indexPart);

        return [$statement];
    }

    /**
     * @param IndexPart $indexPart
     *
     * @return string
     */
    private function buildIndexPart(IndexPart $indexPart) : string
    {

        $sql = $this->quote($indexPart->getColumnName());
        if ($indexPart->getLength()) {
            $sql .= " (" . $indexPart->getLength() . ")";
        }

        $sql .= " " . $indexPart->getSortOrder();

        return $sql;
    }

    /**
     * @param IndexMetaData $index
     *
     * @return string[]
     */
    public function createDropIndexStatement(IndexMetaData $index): array
    {
        $indexNameQuoted = $this->quote($index->getName());
        $statement = sprintf(self::DROP_INDEX, $this->tableName, $indexNameQuoted);
        return [$statement];
    }

}