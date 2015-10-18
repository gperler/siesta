<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 13.10.15
 * Time: 20:07
 */

namespace siestaphp\driver;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
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


    public function createAddIndexStatement();

    public function createDropIndexStatement();

}