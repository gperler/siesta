<?php

declare(strict_types = 1);

namespace Siesta\XML;

class XMLValueObject
{

    const ELEMENT_VALUE_OBJECT = "valueObject";

    const CLASS_NAME = "className";

    /**
     * @var string
     */
    protected $className;

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setClassName($xmlAccess->getAttribute(self::CLASS_NAME));
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className = null)
    {
        $this->className = $className;
    }

}