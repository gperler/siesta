<?php

namespace siestaphp\xmlreader;

use siestaphp\datamodel\construct\ConstructSource;
use siestaphp\naming\XMLConstruct;

/**
 * Class XMLConstructReader
 * @package siestaphp\xmlreaderﬂ
 */
class XMLConstructReader extends XMLAccess implements ConstructSource
{

    /**
     * @return string
     */
    public function getConstructorClass()
    {
        return $this->getAttribute(XMLConstruct::ATTRIBUTE_CLASS_NAME);
    }

    /**
     * @return string
     */
    public function getConstructorNamespace()
    {
        return $this->getAttribute(XMLConstruct::ATTRIBUTE_CLASS_NAMESPACE);
    }

    /**
     * @return string
     */
    public function getFullyQualifiedClassName()
    {
        if (empty($this->getConstructorClass())) {
            return null;
        }
        return $this->getConstructorNamespace() . "\\" . $this->getConstructorClass();
    }

    /**
     * @return string
     */
    public function getConstructFactory()
    {
        return $this->getAttribute(XMLConstruct::ATTRIBUTE_CONSTRUCT_FACTORY);
    }

    /**
     * @return string
     */
    public function getConstructFactoryFqn()
    {
        return $this->getAttribute(XMLConstruct::ATTRIBUTE_CONSTRUCT_FACTORY_FQN);
    }

}