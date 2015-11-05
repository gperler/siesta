<?php

namespace siestaphp\driver;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\datamodel\reference\ReferenceSource;

/**
 * Interface ColumnMigrator
 * @package siestaphp\driver
 */
interface ColumnMigrator
{

    const TABLE_PLACE_HOLDER = "!TABLE!";

    /**
     * @param EntitySource $asIs
     * @param EntityGeneratorSource $toBe
     *
     * @return string[]
     */
    public function getModifyPrimaryKeyStatement(EntitySource $asIs, EntityGeneratorSource $toBe);

    /**
     * @param EntitySource $entitySource
     *
     * @return string
     */
    public function getDropTableStatement(EntitySource $entitySource);

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createDropColumnStatement(DatabaseColumn $column);

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createAddColumnStatement(DatabaseColumn $column);

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function createModifiyColumnStatement(DatabaseColumn $column);

    /**
     * @param ReferenceGeneratorSource $reference
     *
     * @return string
     */
    public function createAddForeignKeyStatement(ReferenceGeneratorSource $reference);

    /**
     * @param ReferenceSource $reference
     *
     * @return string
     */
    public function createDropForeignKeyStatement(ReferenceSource $reference);

    /**
     * @param IndexSource $indexSource
     *
     * @return string
     */
    public function createAddIndexStatement(IndexSource $indexSource);

    /**
     * @param IndexSource $indexSource
     *
     * @return string
     */
    public function createDropIndexStatement(IndexSource $indexSource);

}