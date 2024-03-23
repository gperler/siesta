<?php
declare(strict_types=1);

namespace Siesta\Model;

use Siesta\Util\StringUtil;
use Siesta\XML\XMLConstructor;

class Constructor
{

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
     * @param XMLConstructor $xmlConstructor
     */
    public function fromXMLConstructor(XMLConstructor $xmlConstructor): void
    {
        $this->setClassName($xmlConstructor->getClassName());
        $this->setConstructCall($xmlConstructor->getConstructCall());
        $this->setConstructFactoryClassName($xmlConstructor->getConstructFactoryClassName());
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
     * @return null|string
     */
    public function getClassShortName(): ?string
    {
        if ($this->className === null) {
            return null;
        }
        return StringUtil::getEndAfterLast($this->getClassName(), "\\");
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