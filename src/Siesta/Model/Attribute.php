<?php
declare(strict_types = 1);

namespace Siesta\Model;

use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\Util\StringUtil;
use Siesta\Validator\DefaultAttributeValidator;
use Siesta\XML\XMLAttribute;

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
     * @var boolean
     */
    protected $isRequired;

    /**
     * @var boolean
     */
    protected $isTransient;

    /**
     * @var boolean
     */
    protected $isObject;

    /**
     * @var string
     */
    protected $className;

    /**
     * Attribute constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param XMLAttribute $xmlAttribute
     */
    public function fromXMLAttribute(XMLAttribute $xmlAttribute)
    {
        $this->setAutoValue($xmlAttribute->getAutoValue());
        $this->setDbType($xmlAttribute->getDbType());
        $this->setDbName($xmlAttribute->getDbName());
        $this->setDefaultValue($xmlAttribute->getDefaultValue());
        $this->setIsPrimaryKey($xmlAttribute->getIsPrimaryKey());
        $this->setIsRequired($xmlAttribute->getIsRequired());
        $this->setIsTransient($xmlAttribute->getIsTransient());
        $this->setPhpName($xmlAttribute->getPhpName());
        $this->setPhpType($xmlAttribute->getPhpType());
    }

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
        $classShortName = StringUtil::getEndAfterLast($this->getPhpType(), "\\");
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
    public function setPhpName($phpName)
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
    public function setPhpType($phpType)
    {
        $this->phpType = $phpType;
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
    public function setDbName($dbName)
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
    public function setDbType($dbType)
    {
        $this->dbType = $dbType;
    }

    /**
     * @return boolean
     */
    public function getIsPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * @param boolean $isPrimaryKey
     */
    public function setIsPrimaryKey($isPrimaryKey)
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
    public function setDefaultValue($defaultValue)
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
    public function setAutoValue($autoValue)
    {
        $this->autoValue = $autoValue;
    }

    /**
     * @return boolean
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param boolean $isRequired
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return boolean
     */
    public function getIsTransient()
    {
        return $this->isTransient;
    }

    /**
     * @param boolean $isTransient
     */
    public function setIsTransient($isTransient)
    {
        $this->isTransient = $isTransient;
    }

    /**
     * @return boolean
     */
    public function getIsObject()
    {
        return $this->isObject;
    }

    /**
     * @param boolean $isObject
     */
    public function setIsObject($isObject)
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
    public function setClassName($className)
    {
        $this->className = $className;
    }

}