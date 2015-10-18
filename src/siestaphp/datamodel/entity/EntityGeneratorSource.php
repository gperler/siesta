<?php

namespace siestaphp\datamodel\entity;

use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\index\IndexGeneratorSource;
use siestaphp\datamodel\reference\ReferenceDatabaseSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\util\File;

/**
 * Interface EntityGeneratorSource
 * @package siestaphp\datamodel\entity
 */
interface EntityGeneratorSource extends EntitySource
{

    /**
     * @return bool
     */
    public function isDateTimeUsed();

    /**
     * @return string[]
     */
    public function getUsedFQClassNames();

    /**
     * @return AttributeGeneratorSource;
     */
    public function getPrimaryKeyAttributeList();

    /**
     * @param $baseDir
     *
     * @return File
     */
    public function getTargetEntityFile($baseDir);

    /**
     * @param $baseDir
     *
     * @return File
     */
    public function getAbsoluteTargetPath($baseDir);

    /**
     * @return bool
     */
    public function hasReferences();

    /**
     * @return bool
     */
    public function hasAttributes();

    /**
     * @return bool
     */
    public function hasPrimaryKey();

    /**
     * @return DatabaseColumn[]
     */
    public function getPrimaryKeyColumns();

    /**
     * @param $database
     *
     * @return DatabaseSpecificSource
     */
    public function getDatabaseSpecific($database);

    /**
     * @return AttributeGeneratorSource[]
     */
    public function getAttributeGeneratorSourceList();

    /**
     * @return ReferenceGeneratorSource[]
     */
    public function getReferenceGeneratorSourceList();

    /**
     * @return IndexGeneratorSource[]
     */
    public function getIndexGeneratorSourceList();

}