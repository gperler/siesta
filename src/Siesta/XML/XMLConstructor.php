<?php
declare(strict_types=1);

namespace Siesta\XML;

class XMLConstructor
{

    const ELEMENT_CONSTRUCTOR_NAME = "constructor";

    const CONSTRUCT_CLASS = "className";

    const CONSTRUCT_CALL = "constructCall";

    const CONSTRUCT_FACTORY_FQN = "constructFactoryClassName";

    /**
     * @var string|null
     */
    protected ?string $className;

    /**
     * @var string|null
     */
    protected ?string $constructCall;

    /**
     * @var string|null
     */
    protected ?string $constructFactoryClassName;

    /**
     *
     */
    public function __construct()
    {
        $this->className = null;
        $this->constructCall = null;
        $this->constructFactoryClassName = null;
    }


    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setClassName($xmlAccess->getAttribute(self::CONSTRUCT_CLASS));
        $this->setConstructCall($xmlAccess->getAttribute(self::CONSTRUCT_CALL));
        $this->setConstructFactoryClassName($xmlAccess->getAttribute(self::CONSTRUCT_FACTORY_FQN));
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @param string|null $className
     */
    public function setClassName(?string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return string|null
     */
    public function getConstructCall(): ?string
    {
        return $this->constructCall;
    }

    /**
     * @param string|null $constructCall
     */
    public function setConstructCall(?string $constructCall): void
    {
        $this->constructCall = $constructCall;
    }

    /**
     * @return string|null
     */
    public function getConstructFactoryClassName(): ?string
    {
        return $this->constructFactoryClassName;
    }

    /**
     * @param string|null $constructFactoryClassName
     */
    public function setConstructFactoryClassName(?string $constructFactoryClassName): void
    {
        $this->constructFactoryClassName = $constructFactoryClassName;
    }

}