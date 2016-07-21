<?php
declare(strict_types = 1);

namespace Siesta\XML;

class XMLServiceClass
{

    const ELEMENT_SERVICE_CLASS_NAME = "serviceClass";

    const CLASS_NAME = "className";

    const CONSTRUCT_CALL = "constructCall";

    const CONSTRUCT_FACTORY_CLASS_NAME = "constructFactoryClassName";

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $constructCall;

    /**
     * @var string
     */
    protected $constructFactoryClassName;

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXMLAccess(XMLAccess $xmlAccess)
    {
        $this->setClassName($xmlAccess->getAttribute(self::CLASS_NAME));
        $this->setConstructCall($xmlAccess->getAttribute(self::CONSTRUCT_CALL));
        $this->setConstructFactoryClassName($xmlAccess->getAttribute(self::CONSTRUCT_FACTORY_CLASS_NAME));
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
    public function setClassName($className)
    {
        $this->className = trim($className, "\\");
    }

    /**
     * @return string
     */
    public function getConstructCall()
    {
        return $this->constructCall;
    }

    /**
     * @param string $constructCall
     */
    public function setConstructCall($constructCall)
    {
        $this->constructCall = $constructCall;
    }

    /**
     * @return string
     */
    public function getConstructFactoryClassName()
    {
        return $this->constructFactoryClassName;
    }

    /**
     * @param string $constructFactoryClassName
     */
    public function setConstructFactoryClassName($constructFactoryClassName)
    {
        $this->constructFactoryClassName = $constructFactoryClassName;
    }

}

