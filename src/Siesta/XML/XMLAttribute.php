<?php
declare(strict_types=1);

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
     * @var string[]
     */
    protected array $customAttributeList;

    public function __construct()
    {
        $this->phpName = null;
        $this->phpType = null;
        $this->dbName = null;
        $this->dbType = null;
        $this->isPrimaryKey = false;
        $this->defaultValue = null;
        $this->autoValue = null;
        $this->isRequired = false;
        $this->isTransient = false;
        $this->customAttributeList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
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
        $this->setCustomAttributeList($xmlAccess->getAttributeList());
    }

    /**
     * @param XMLWrite $parent
     */
    public function toXML(XMLWrite $parent): void
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
    public function fromColumnMetaData(ColumnMetaData $columnMetaData): void
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
     * @return string|null
     */
    public function getPhpName(): ?string
    {
        return $this->phpName;
    }

    /**
     * @param string|null $phpName
     */
    public function setPhpName(?string $phpName): void
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
    public function setPhpType(?string $phpType): void
    {
        $this->phpType = $phpType;
    }

    /**
     * @return string|null
     */
    public function getDbName(): ?string
    {
        return $this->dbName;
    }

    /**
     * @param string|null $dbName
     */
    public function setDbName(?string $dbName): void
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
    public function setDbType(?string $dbType): void
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
    public function setIsPrimaryKey(bool $isPrimaryKey): void
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
    public function setDefaultValue(?string $defaultValue): void
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
    public function setAutoValue(?string $autoValue): void
    {
        $this->autoValue = $autoValue;
    }

    /**
     * @return bool
     */
    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired(bool $isRequired): void
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
    public function setIsTransient(bool $isTransient): void
    {
        $this->isTransient = $isTransient;
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