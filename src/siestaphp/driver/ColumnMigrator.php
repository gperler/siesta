<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 13.10.15
 * Time: 20:07
 */

namespace siestaphp\driver;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\attribute\AttributeTransformerSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\reference\ReferenceTransformerSource;

/**
 * Interface ColumnMigrator
 * @package siestaphp\driver
 */
interface ColumnMigrator
{

    const TABLE_PLACE_HOLDER = "!TABLE!";

    /**
     * @param EntitySource $entitySource
     *
     * @return string
     */
    public function getDropTableStatement(EntitySource $entitySource);

    /**
     * @param ReferenceSource $asIs
     * @param ReferenceTransformerSource $toBe
     *
     * @return string[]
     */
    public function getReferenceAlterStatement(ReferenceSource $asIs, ReferenceTransformerSource $toBe);

    /**
     * @param AttributeSource $asIs
     * @param AttributeTransformerSource $toBe
     *
     * @return string[]
     */
    public function getAttributeAlterStatement(AttributeSource $asIs, AttributeTransformerSource $toBe);

    /**
     * @param IndexSource $asIs
     * @param IndexSource $toBe
     *
     * @return string[]
     */
    public function getIndexAlterStatement(IndexSource $asIs, IndexSource $toBe);

}