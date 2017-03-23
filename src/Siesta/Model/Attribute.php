<?php
declare(strict_types = 1);

namespace Siesta\Model;

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
    protected $entity;

    /**
     * @var string
     */
    protected $phpName;

    /**
     * @var string
     */
    protected $phpType;

    /**
     * @var string
     */
    protected $dbName;

    /**
     * @var string
     */
    protected $dbType;

    /**
     * @var bool
     */
    protected $isPrimaryKey;

    /**
     * @var string
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $autoValue;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var bool
     */
    protected $isTransient;

    /**
     * @var bool
     */
    protected $isObject;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var bool
     */
    protected $isForeignKey;

    /**
     * @var string[]
     */
    protected $customAttributeList;

    /**
     * Attribute constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->isForeignKey = false;
    }

    /**
     *
     */
    public function update()
    {
        $this->checkIfTypeIsObject();
    }

    /**
     *
     */
    protected function checkIfTypeIsObject()
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
    public function getLength()
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
    public function getMethodName()
    {
        return ucfirst($this->getPhpName());
    }

    /**
     * @return string
     */
    public function getPhpName()
    {
        return $this->phpName;
    }

    /**
     * @param string $phpName
     */
    public function setPhpName(string $phpName = null)
    {
        $this->phpName = $phpName;
    }

    /**
     * @return string
     */
    public function getPhpType()
    {
        return $this->phpType;
    }

    /**
     * @param string $phpType
     */
    public function setPhpType(string $phpType = null)
    {
        $this->phpType = $phpType;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedTypeName()
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
     * @return string
     */
    public function getDBName()
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
    public function getStoredProcedureParameterName() : string
    {
        return "P_" . strtoupper($this->getDBName());
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName = null)
    {
        $this->dbName = $dbName;
    }

    /**
     * @return string
     */
    public function getDbType()
    {
        return $this->dbType;
    }

    /**
     * @param string $dbType
     */
    public function setDbType(string $dbType = null)
    {
        $this->dbType = $dbType;
    }

    /**
     * @return bool
     */
    public function getIsPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * @param bool $isPrimaryKey
     */
    public function setIsPrimaryKey(bool $isPrimaryKey = false)
    {
        $this->isPrimaryKey = $isPrimaryKey;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue(string $defaultValue = null)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getAutoValue()
    {
        return $this->autoValue;
    }

    /**
     * @param string $autoValue
     */
    public function setAutoValue(string $autoValue = null)
    {
        $this->autoValue = $autoValue;
    }

    /**
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->isRequired || $this->isPrimaryKey;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired(bool $isRequired = false)
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return bool
     */
    public function getIsTransient()
    {
        return $this->isTransient;
    }

    /**
     * @param bool $isTransient
     */
    public function setIsTransient(bool $isTransient = false)
    {
        $this->isTransient = $isTransient;
    }

    /**
     * @return bool
     */
    public function getIsObject()
    {
        return $this->isObject;
    }

    /**
     * @param bool $isObject
     */
    public function setIsObject(bool $isObject = false)
    {
        $this->isObject = $isObject;
    }

    /**
     * @return bool
     */
    public function implementsArraySerializable() : bool
    {
        if (!$this->getIsObject()) {
            return false;
        }
        $reflect = new \ReflectionClass($this->getClassName());
        return $reflect->implementsInterface(self::INTERFACE_ARRAY_SERIALIZABLE);
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
     * @return bool
     */
    public function getIsForeignKey(): bool
    {
        return $this->isForeignKey;
    }

    /**
     *
     */
    public function setIsForeignKey()
    {
        $this->isForeignKey = true;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getCustomAttribute(string $name)
    {
        return ArrayUtil::getFromArray($this->customAttributeList, $name);
    }

    /**
     * @return \string[]
     */
    public function getCustomAttributeList(): array
    {
        return $this->customAttributeList;
    }

    /**
     * @param \string[] $customAttributeList
     */
    public function setCustomAttributeList(array $customAttributeList)
    {
        $this->customAttributeList = $customAttributeList;
    }

}