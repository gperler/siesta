<?php

declare(strict_types = 1);

namespace Siesta\XML;

class XMLValueObject
{

    const ELEMENT_VALUE_OBJECT = "valueObject";

    const CLASS_NAME = "className";

    const MEMBER_NAME = "memberName";

    /**
     * @var string
     */
    protected $className;

    protected $memberName;

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setClassName($xmlAccess->getAttribute(self::CLASS_NAME));
        $this->setMemberName($xmlAccess->getAttribute(self::MEMBER_NAME));

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

    /**
     * @return string
     */
    public function getMemberName()
    {
        return $this->memberName;
    }

    /**
     * @param string $memberName
     */
    public function setMemberName(string $memberName = null)
    {
        $this->memberName = $memberName;
    }

}