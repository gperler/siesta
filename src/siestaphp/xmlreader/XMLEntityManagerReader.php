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

    /**
     * @return string
     */
    public function getConstructFactory()
    {
        return $this->getAttribute(XMLEntityManager::ATTRIBUTE_CONSTRUCT_FACTORY);
    }

    /**
     * @return string
     */
    public function getConstructFactoryFqn()
    {
        return $this->getAttribute(XMLEntityManager::ATTRIBUTE_CONSTRUCT_FACTORY_FQN);
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName()
    {
        return null;
    }

}