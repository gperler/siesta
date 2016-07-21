<?php
declare(strict_types = 1);
namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface ConstraintMetaData
{

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return string
     */
    public function getConstraintName() : string;

    /**
     * @return string
     */
    public function getForeignTable() : string;

    /**
     * @return string
     */
    public function getOnUpdate() : string;

    /**
     * @return string
     */
    public function getOnDelete() : string;

    /**
     * @return ConstraintMappingMetaData[]
     */
    public function getConstraintMappingList() : array;

}