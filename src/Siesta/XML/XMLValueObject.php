<?php

declare(strict_types=1);

namespace Siesta\XML;

class XMLValueObject
{

    const ELEMENT_VALUE_OBJECT = "valueObject";

    const CLASS_NAME = "className";

    const MEMBER_NAME = "memberName";

    /**
     * @var string|null
     */
    protected ?string $className;

    /**
     * @var string|null
     */
    protected ?string $memberName;


    /**
     *
     */
    public function __construct()
    {
        $this->className = null;
        $this->memberName = null;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setClassName($xmlAccess->getAttribute(self::CLASS_NAME));
        $this->setMemberName($xmlAccess->getAttribute(self::MEMBER_NAME));

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
    public function getMemberName(): ?string
    {
        return $this->memberName;
    }

    /**
     * @param string|null $memberName
     */
    public function setMemberName(?string $memberName): void
    {
        $this->memberName = $memberName;
    }


}