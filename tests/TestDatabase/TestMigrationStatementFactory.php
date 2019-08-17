<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Database\MigrationStatementFactory;
use Siesta\Model\Attribute;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
class TestMigrationStatementFactory implements MigrationStatementFactory
{
    protected $table;

    public function __construct()
    {
        $this->table = "'" . MigrationStatementFactory::TABLE_PLACE_HOLDER . "'";
    }

    public function getModifyPrimaryKeyStatement(TableMetaData $table, Entity $entity) : array
    {
        return ["table " . $this->table . " modify pk"];
    }

    public function getDropTableStatement(TableMetaData $table) : array
    {
        return ["drop table " . $this->table];
    }

    public function createDropColumnStatement(ColumnMetaData $column): array
    {
        return ["table " . $this->table . " drop colum '" . $column->getDBName() . "'"];
    }

    public function createAddColumnStatement(Attribute $attribute): array
    {
        return ["table " . $this->table . " add column '" . $attribute->getDBName() . "'"];
    }

    public function createModifyColumnStatement(Attribute $attribute): array
    {
        return ["table " . $this->table . " modifiy column '" . $attribute->getDBName() . "'"];
    }

    public function createAddReferenceStatement(Reference $reference): array
    {
        return ["table " . $this->table . " add reference '" . $reference->getConstraintName() . "'"];
    }

    public function createDropConstraintStatement(ConstraintMetaData $constraint): array
    {
        return ["table " . $this->table . " drop constraint '" . $constraint->getConstraintName() . "'"];
    }

    public function createAddIndexStatement(Index $index): array
    {
        return ["table " . $this->table . " add index '" . $index->getName() . "'"];
    }

    public function createDropIndexStatement(IndexMetaData $index): array
    {
        return ["table " . $this->table . " drop index '" . $index->getName() . "'"];
    }

}