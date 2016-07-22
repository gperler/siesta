<?php
declare(strict_types = 1);

namespace Siesta\XML;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\NamingStrategy\NamingStrategyRegistry;

/**
 * @author Gregor Müller
 */
class XMLAttribute
{

    const ELEMENT_ATTRIBUTE_NAME = "attribute";

    const PHP_NAME = "name";

    const PHP_TYPE = "type";

    const DB_NAME = "dbName";

    const DB_TYPE = "dbType";

    const DEFAULT_VALUE = "defaultValue";

    const PRIMARY_KEY = "primaryKey";

    const REQUIRED = "required";

    const AUTO_VALUE = "autoValue";

    const TRANSIENT = "transient";

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
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setAutoValue($xmlAccess->getAttribute(self::AUTO_VALUE));
        $this->setPhpName($xmlAccess->getAttribute(self::PHP_NAME));
        $this->setPhpType($xmlAccess->getAttribute(self::PHP_TYPE));
        $this->setDbName($xmlAccess->getAttribute(self::DB_NAME));
        $this->setDbType($xmlAccess->getAttribute(self::DB_TYPE));
        $this->setDefaultValue($xmlAccess->getAttribute(self::DEFAULT_VALUE));
        $this->setIsPrimaryKey($xmlAccess->getAttributeAsBool(self::PRIMARY_KEY));
        $this->setIsRequired($xmlAccess->getAttributeAsBool(self::REQUIRED));
        $this->setIsTransient($xmlAccess->getAttributeAsBool(self::TRANSIENT));
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent)
    {
        $xmlWrite = $parent->appendChild(self::ELEMENT_ATTRIBUTE_NAME);
        $xmlWrite->setAttribute(self::PHP_NAME, $this->getPhpName());
        $xmlWrite->setAttribute(self::PHP_TYPE, $this->getPhpType());
        $xmlWrite->setAttribute(self::DB_NAME, $this->getDbName());
        $xmlWrite->setAttribute(self::DB_TYPE, $this->getDbType());
        $xmlWrite->setBoolAttributeIfTrue(self::PRIMARY_KEY, $this->getIsPrimaryKey());
        $xmlWrite->setBoolAttributeIfTrue(self::REQUIRED, $this->getIsRequired());

        if ($this->getAutoValue() !== null) {
            $xmlWrite->setAttribute(self::AUTO_VALUE, $this->getAutoValue());
        }
    }

    /**
     * @param ColumnMetaData $columnMetaData
     */
    public function fromColumnMetaData(ColumnMetaData $columnMetaData)
    {
        $namingStrategy = NamingStrategyRegistry::getAttributeNamingStrategy();
        $phpName = $namingStrategy->transform($columnMetaData->getDBName());
        $this->setPhpName($phpName);
        $this->setPhpType($columnMetaData->getPHPType());
        $this->setDbName($columnMetaData->getDBName());
        $this->setDbType($columnMetaData->getDBType());

        $this->setIsPrimaryKey($columnMetaData->getIsPrimaryKey());
        $this->setIsRequired($columnMetaData->getIsRequired());
        $this->setAutoValue($columnMetaData->getAutoValue());
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
    public function getDbName()
    {
        return $this->dbName;
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
     * @return bool
     */
    public function getIsPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * @param bool $isPrimaryKey
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
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired($isRequired)
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
    public function setIsTransient($isTransient)
    {
        $this->isTransient = $isTransient;
    }

}