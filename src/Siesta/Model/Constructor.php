<?php
declare(strict_types = 1);

namespace Siesta\Model;

use Siesta\Util\StringUtil;
use Siesta\XML\XMLConstructor;

class Constructor
{

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
     * @param XMLConstructor $xmlConstructor
     */
    public function fromXMLConstructor(XMLConstructor $xmlConstructor)
    {
        $this->setClassName($xmlConstructor->getClassName());
        $this->setConstructCall($xmlConstructor->getConstructCall());
        $this->setConstructFactoryClassName($xmlConstructor->getConstructFactoryClassName());
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
     * @return null|string
     */
    public function getClassShortName()
    {
        if ($this->className === null) {
            return null;
        }
        return StringUtil::getEndAfterLast($this->getClassName(), "\\");
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