<?php


namespace siestaphp\datamodel\entity;
use siestaphp\datamodel\attribute\AttributeTransformerSource;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\util\File;


/**
 * Interface EntityTransformerSource
 * @package siestaphp\datamodel
 */
interface EntityTransformerSource extends EntitySource {


    /**
     * @return bool
     */
    public function isDateTimeUsed();


    /**
     * @return string[]
     */
    public function getUsedFQClassNames();


    /**
     * @return AttributeTransformerSource;
     */
    public function getPrimaryKeyAttributeList();


    /**
     * @param $baseDir
     * @return File
     */
    public function getTargetEntityFile($baseDir);


    /**
     * @param $baseDir
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
     * @return string
     */
    public function getFindByPKSignature();


    /**
     * @return string
     */
    public function getSPCallSignature();

    /**
     * @return bool
     */
    public function hasPrimaryKey();

    /**
     * @return DatabaseColumn[]
     */
    public function getPrimaryKeyColumns();

}