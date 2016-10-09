<?php

declare(strict_types = 1);

namespace Siesta\Model;

use Siesta\Util\StringUtil;

class ValueObject
{

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $memberName;

    /**
     * @var Attribute[]
     */
    protected $attributeList;

    /**
     * ValueObject constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->attributeList = [];
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

    /**
     *
     */
    public function update()
    {
        $reflect = new \ReflectionClass($this->className);

        foreach ($this->entity->getAttributeList() as $attribute) {
            $setter = 'set' . $attribute->getMethodName();
            $getter = 'get' . $attribute->getMethodName();

            if ($reflect->hasMethod($setter) && $reflect->hasMethod($getter)) {
                $this->attributeList[] = $attribute;
            }
        }
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
    public function getMethodName()
    {
        if ($this->memberName !== null) {
            return ucfirst($this->memberName);
        }
        return ucfirst($this->getClassShortName());
    }

    /**
     * @return string
     */
    public function getPhpName()
    {
        if ($this->memberName !== null) {
            return lcfirst($this->memberName);
        }

        return lcfirst($this->getClassShortName());
    }

    /**
     * @return Attribute[]
     */
    public function getAttributeList(): array
    {
        return $this->attributeList;
    }

}