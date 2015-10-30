<?php

namespace siestaphp\datamodel\collector;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\reference\ReferenceSource;

/**
 * Interface CollectorGeneratorSource
 * @package siestaphp\datamodel
 */
interface CollectorGeneratorSource extends CollectorSource
{

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return string
     */
    public function getForeignConstructClass();

    /**
     * @return string
     */
    public function getReferenceMethodName();

    /**
     * @return EntitySource
     */
    public function getMappingClassEntity();

    /**
     * @return ReferenceSource
     */
    public function getReference();

    /**
     * @return null|string
     */
    public function getNMMappingMethodName();


    /**
     * @return string
     */
    public function getNMThisMethodName();

    /**
     * @return string
     */
    public function getNMForeignMethodName();
}