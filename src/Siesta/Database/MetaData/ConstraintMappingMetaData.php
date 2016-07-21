<?php
declare(strict_types = 1);
namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface ConstraintMappingMetaData
{

    /**
     * @return string
     */
    public function getForeignColumn() : string;

    /**
     * @return string
     */
    public function getLocalColumn() : string;
}