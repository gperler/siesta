<?php

declare(strict_types=1);

namespace Siesta\Model;

use ReflectionClass;
use ReflectionException;
use Siesta\Util\StringUtil;

class ValueObject
{

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string|null
     */
    protected ?string $className;

    /**
     * @var string|null
     */
    protected ?string $memberName;

    /**
     * @var Attribute[]
     */
    protected array $attributeList;

    /**
     * ValueObject constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->className = null;
        $this->memberName = null;
        $this->attributeList = [];
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
    public function setClassName(string $className = null): void
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
    public function setMemberName(string $memberName = null): void
    {
        $this->memberName = $memberName;
    }

    /**
     * @throws ReflectionException
     */
    public function update(): void
    {
        $reflect = new ReflectionClass($this->className);

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
    public function getClassShortName(): ?string
    {
        if ($this->className === null) {
            return null;
        }
        return StringUtil::getEndAfterLast($this->getClassName(), "\\");
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        if ($this->memberName !== null) {
            return ucfirst($this->memberName);
        }
        return ucfirst($this->getClassShortName());
    }

    /**
     * @return string
     */
    public function getPhpName(): string
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