<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\manager\EntityManagerSource;
use siestaphp\naming\XMLEntityManager;

/**
 * Class XMLEntityManagerReader
 * @package siestaphp\xmlreader
 */
class XMLEntityManagerReader extends XMLAccess implements EntityManagerSource
{
    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getAttribute(XMLEntityManager::ATTRIBUTE_CLASS_NAME);
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
        return $this->getAttribute(XMLEntityManager::ATTRIBUTE_CLASS_NAMESPACE);
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->getAttribute(XMLEntityManager::ATTRIBUTE_TARGET_PATH);
    }

}