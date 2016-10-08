<?php

declare(strict_types = 1);

namespace Siesta\Model;

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

}