<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Util\StringUtil;

class XMLConstructor
{

    const ELEMENT_CONSTRUCTOR_NAME = "constructor";

    const CONSTRUCT_CLASS = "className";

    const CONSTRUCT_CALL = "constructCall";

    const CONSTRUCT_FACTORY_FQN = "constructFactoryClassName";

    protected $className;

    protected $constructCall;

    protected $constructFactoryClassName;

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setClassName($xmlAccess->getAttribute(self::CONSTRUCT_CLASS));
        $this->setConstructCall($xmlAccess->getAttribute(self::CONSTRUCT_CALL));
        $this->setConstructFactoryClassName($xmlAccess->getAttribute(self::CONSTRUCT_FACTORY_FQN));
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
        $this->className = $className;
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