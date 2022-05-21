<?php
declare(strict_types = 1);
namespace Siesta\Database;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\Model\Attribute;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
interface MigrationStatementFactory
{

    const TABLE_PLACE_HOLDER = "!TABLE!";

    /**
     * @param TableMetaData $table
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getModifyPrimaryKeyStatement(TableMetaData $table, Entity $entity) : array;

    /**
     * @param TableMetaData $table
     *
     * @return string[]
     */
    public function getDropTableStatement(TableMetaData $table) : array;

    /**
     * @param ColumnMetaData $column
     *
     * @return string[]
     */
    public function createDropColumnStatement(ColumnMetaData $column): array;

    /**
     * @param Attribute $attribute
     *
     * @return string[]
     */
    public function createAddColumnStatement(Attribute $attribute): array;

    /**
     * @param  Attribute $attribute
     *
     * @return string[]
     */
    public function createModifyColumnStatement(Attribute $attribute): array;

    /**
     * @param Reference $reference
     *
     * @return string[]
     */
    public function createAddReferenceStatement(Reference $reference): array;

    /**
     * @param ConstraintMetaData $constraint
     *
     * @return string[]
     */
    public function createDropConstraintStatement(ConstraintMetaData $constraint): array;

    /**
     * @param Index $index
     *
     * @return string[]
     */
    public function createAddIndexStatement(Index $index): array;

    /**
     * @param IndexMetaData $index
     *
     * @return string[]
     */
    public function createDropIndexStatement(IndexMetaData $index): array;


    /**
     * @param string $tableName
     *
     * @return string
     */
    public function createLockTable(string $tableName) : string;


    /**
     * @param string $tableName
     *
     * @return string
     */
    public function createUnlockTable(string $tableName) : string;
}