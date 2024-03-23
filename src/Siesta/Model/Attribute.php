<?php
declare(strict_types=1);

namespace Siesta\Model;

use ReflectionClass;
use ReflectionException;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\Util\ArrayUtil;
use Siesta\Util\StringUtil;
use Siesta\Validator\DefaultAttributeValidator;

/**
 * @author Gregor Müller
 */
class Attribute
{

    const INTERFACE_ARRAY_SERIALIZABLE = 'Siesta\Contract\ArraySerializable';

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string|null
     */
    protected ?string $phpName;

    /**
     * @var string|null
     */
    protected ?string $phpType;

    /**
     * @var string|null
     */
    protected ?string $dbName;

    /**
     * @var string|null
     */
    protected ?string $dbType;

    /**
     * @var bool
     */
    protected bool $isPrimaryKey;

    /**
     * @var string|null
     */
    protected ?string $defaultValue;

    /**
     * @var string|null
     */
    protected ?string $autoValue;

    /**
     * @var bool
     */
    protected bool $isRequired;

    /**
     * @var bool
     */
    protected bool $isTransient;

    /**
     * @var bool
     */
    protected bool $isObject;

    /**
     * @var string|null
     */
    protected ?string $className;

    /**
     * @var bool
     */
    protected bool $isForeignKey;

    /**
     * @var string[]
     */
    protected array $customAttributeList;

    /**
     * Attribute constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->isForeignKey = false;
        $this->isObject = false;
        $this->isPrimaryKey = false;
        $this->isRequired = false;
        $this->isTransient = false;
        $this->customAttributeList = [];
        $this->className = null;
        $this->dbName = null;
        $this->autoValue = null;
        $this->defaultValue = null;
    }

    /**
     *
     */
    public function update(): void
    {
        $this->checkIfTypeIsObject();
    }

    /**
     *
     */
    protected function checkIfTypeIsObject(): void
    {
        $phpType = $this->getPhpType();
        if ($phpType === null || in_array($phpType, DefaultAttributeValidator::PHP_TYPE_LIST)) {
            return;
        }

        $this->setIsObject(true);
        $this->setClassName($this->getPhpType());
        $classShortName = StringUtil::getEndAfterLast($phpType, "\\");
        $this->setPhpType($classShortName);
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        if ($this->getDbType() === null) {
            return null;
        }
        if (preg_match("/VARCHAR\((.*?)\)/i", $this->getDbType(), $regResult)) {
            return (int)$regResult [1];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return ucfirst($this->getPhpName());
    }

    /**
     * @return string|null
     */
    public function getPhpName(): ?string
    {
        return $this->phpName;
    }

    /**
     * @param string|null $phpName
     */
    public function setPhpName(string $phpName = null): void
    {
        $this->phpName = $phpName;
    }

    /**
     * @return string|null
     */
    public function getPhpType(): ?string
    {
        return $this->phpType;
    }

    /**
     * @param string|null $phpType
     */
    public function setPhpType(string $phpType = null): void
    {
        $this->phpType = $phpType;
    }

    /**
     * @return string|null
     */
    public function getFullyQualifiedTypeName(): ?string
    {
        if ($this->phpType === PHPType::SIESTA_DATE_TIME) {
            return PHPType::SIESTA_DATE_TIME_CLASS_NAME;
        }
        if ($this->className !== null) {
            return $this->className;
        }
        return $this->phpType;
    }

    /**
     * @return string|null
     */
    public function getDBName(): ?string
    {
        if ($this->dbName !== null) {
            return $this->dbName;
        }
        $columnNaming = NamingStrategyRegistry::getColumnNamingStrategy();
        return $columnNaming->transform($this->getPhpName());
    }

    /**
     * @return string
     */
    public function getStoredProcedureParameterName(): string
    {
        return "P_" . strtoupper($this->getDBName());
    }

    /**
     * @param string|null $dbName
     */
    public function setDbName(string $dbName = null): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @return string|null
     */
    public function getDbType(): ?string
    {
        return $this->dbType;
    }

    /**
     * @param string|null $dbType
     */
    public function setDbType(string $dbType = null): void
    {
        $this->dbType = $dbType;
    }

    /**
     * @return bool
     */
    public function getIsPrimaryKey(): bool
    {
        return $this->isPrimaryKey;
    }

    /**
     * @param bool $isPrimaryKey
     */
    public function setIsPrimaryKey(bool $isPrimaryKey = false): void
    {
        $this->isPrimaryKey = $isPrimaryKey;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    /**
     * @param string|null $defaultValue
     */
    public function setDefaultValue(string $defaultValue = null): void
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string|null
     */
    public function getAutoValue(): ?string
    {
        return $this->autoValue;
    }

    /**
     * @param string|null $autoValue
     */
    public function setAutoValue(string $autoValue = null): void
    {
        $this->autoValue = $autoValue;
    }

    /**
     * @return bool
     */
    public function getIsRequired(): bool
    {
        return $this->isRequired || $this->isPrimaryKey;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired(bool $isRequired = false): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return bool
     */
    public function getIsTransient(): bool
    {
        return $this->isTransient;
    }

    /**
     * @param bool $isTransient
     */
    public function setIsTransient(bool $isTransient = false): void
    {
        $this->isTransient = $isTransient;
    }

    /**
     * @return bool
     */
    public function getIsObject(): bool
    {
        return $this->isObject;
    }

    /**
     * @param bool $isObject
     */
    public function setIsObject(bool $isObject = false): void
    {
        $this->isObject = $isObject;
    }

    /**
     * @return bool
     * @throws ReflectionException
     */
    public function implementsArraySerializable(): bool
    {
        if (!$this->getIsObject()) {
            return false;
        }
        $reflect = new ReflectionClass($this->getClassName());
        return $reflect->implementsInterface(self::INTERFACE_ARRAY_SERIALIZABLE);
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
     * @return bool
     */
    public function getIsForeignKey(): bool
    {
        return $this->isForeignKey;
    }

    /**
     *
     */
    public function setIsForeignKey(): void
    {
        $this->isForeignKey = true;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getCustomAttribute(string $name): ?string
    {
        return ArrayUtil::getFromArray($this->customAttributeList, $name);
    }

    /**
     * @return string[]
     */
    public function getCustomAttributeList(): array
    {
        return $this->customAttributeList;
    }

    /**
     * @param string[] $customAttributeList
     */
    public function setCustomAttributeList(array $customAttributeList): void
    {
        $this->customAttributeList = $customAttributeList;
    }

}