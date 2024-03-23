<?php

namespace Siesta\Config;

use ReflectionException;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\NamingStrategy\NamingStrategy;
use Siesta\Util\ArrayUtil;
use Siesta\Util\ClassUtil;


class ReverseConfig
{

    const ERROR_NAMING_CLASS_DOES_NOT_EXIST = "%s class '%s' does not exist or is not auto(loaded)";

    const ERROR_NAMING_STRATEGY_DOES_NOT_IMPLEMENT = "%s class '%s' does not implement interface '%s'";

    const NAMING_INTERFACE = 'Siesta\NamingStrategy\NamingStrategy';

    /* targetPath */
    const OPTION_TARGET_PATH = "targetPath";

    const OPTION_TARGET_PATH_DEFAULT = "/reverse";

    /* targetFile */
    const OPTION_TARGET_FILE = "targetFile";

    const OPTION_TARGET_FILE_DEFAULT = "Reverse.xml";

    /* entityFileSuffix */
    const OPTION_ENTITY_FILE_SUFFIX = "entityFileSuffix";

    const OPTION_ENTITY_FILE_SUFFIX_DEFAULT = MainGeneratorConfig::OPTION_ENTITY_FILE_SUFFIX_DEFAULT;

    /* singleFile */
    const OPTION_SINGLE_FILE = "singleFile";

    const OPTION_SINGLE_FILE_DEFAULT = true;

    /* classNamingStrategy */
    const OPTION_CLASS_NAMING = "classNamingStrategy";

    const OPTION_CLASS_NAMING_DEFAULT = 'Siesta\NamingStrategy\ToClassCamelCaseStrategy';

    /* attributeNamingStrategy */
    const OPTION_ATTRIBUTE_NAMING = "attributeNamingStrategy";

    const OPTION_ATTRIBUTE_NAMING_DEFAULT = 'Siesta\NamingStrategy\ToCamelCaseStrategy';

    const OPTION_DEFAULT_NAMESPACE = "defaultNamespace";

    const OPTION_CONNECTION_NAME = "connectionName";

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * @var string
     */
    protected $targetFile;

    /**
     * @var string
     */
    protected $entityXMLFileSuffix;

    /**
     * @var bool
     */
    protected $singleFile;

    /**
     * @var string
     */
    protected $attributeNamingStrategy;

    /**
     * @var string
     */
    protected $classNamingStrategy;

    /**
     * @var string
     */
    protected $defaultNamespace;

    /**
     * @var string
     */
    protected $connectionName;

    /**
     * @param array|null $values
     * @throws ReflectionException
     */
    public function __construct(array $values = null)
    {
        $this->setTargetPath(ArrayUtil::getFromArray($values, self::OPTION_TARGET_PATH));
        $this->setTargetFile(ArrayUtil::getFromArray($values, self::OPTION_TARGET_FILE));
        $this->setEntityXMLFileSuffix(ArrayUtil::getFromArray($values, self::OPTION_ENTITY_FILE_SUFFIX));
        $this->setSingleFile(ArrayUtil::getFromArray($values, self::OPTION_SINGLE_FILE));

        $this->setClassNamingStrategy(ArrayUtil::getFromArray($values, self::OPTION_CLASS_NAMING));
        $this->setAttributeNamingStrategy(ArrayUtil::getFromArray($values, self::OPTION_ATTRIBUTE_NAMING));
        $this->setDefaultNamespace(ArrayUtil::getFromArray($values, self::OPTION_DEFAULT_NAMESPACE));
        $this->setConnectionName(ArrayUtil::getFromArray($values, self::OPTION_CONNECTION_NAME));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::OPTION_TARGET_PATH => $this->getTargetPath(),
            self::OPTION_TARGET_FILE => $this->getTargetFile(),
            self::OPTION_ENTITY_FILE_SUFFIX => $this->getEntityXMLFileSuffix(),
            self::OPTION_SINGLE_FILE => $this->isSingleFile(),
            self::OPTION_CLASS_NAMING => $this->getClassNamingStrategy(),
            self::OPTION_ATTRIBUTE_NAMING => $this->getAttributeNamingStrategy(),
            self::OPTION_DEFAULT_NAMESPACE => $this->getDefaultNamespace(),
            self::OPTION_CONNECTION_NAME => $this->getConnectionName()
        ];
    }

    /**
     * @return string
     */
    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    /**
     * @param string|null $targetPath
     */
    public function setTargetPath(string $targetPath = null): void
    {
        $this->targetPath = ($targetPath !== null) ? $targetPath : self::OPTION_TARGET_PATH_DEFAULT;
    }

    /**
     * @return string
     */
    public function getTargetFile(): string
    {
        return $this->targetFile;
    }

    /**
     * @param string|null $targetFile
     */
    public function setTargetFile(string $targetFile = null): void
    {
        $this->targetFile = $targetFile !== null ? $targetFile : self::OPTION_TARGET_FILE_DEFAULT;
    }

    /**
     * @return string
     */
    public function getEntityXMLFileSuffix(): string
    {
        return $this->entityXMLFileSuffix;
    }

    /**
     * @param string|null $entityXMLFileSuffix
     */
    public function setEntityXMLFileSuffix(string $entityXMLFileSuffix = null): void
    {
        $this->entityXMLFileSuffix = ($entityXMLFileSuffix !== null) ? $entityXMLFileSuffix : self::OPTION_ENTITY_FILE_SUFFIX_DEFAULT;
    }

    /**
     * @return bool
     */
    public function isSingleFile(): bool
    {
        return $this->singleFile;
    }

    /**
     * @param bool $singleFile
     */
    public function setSingleFile(bool $singleFile = null): void
    {
        $this->singleFile = $singleFile !== null ? $singleFile : self::OPTION_SINGLE_FILE_DEFAULT;
    }

    /**
     * @return string
     */
    public function getClassNamingStrategy(): string
    {
        return $this->classNamingStrategy;
    }

    /**
     * @return NamingStrategy
     */
    public function getClassNamingInstance(): NamingStrategy
    {
        $className = $this->getClassNamingStrategy();
        return new $className;
    }

    /**
     * @param string|null $classNamingStrategy
     * @throws ReflectionException
     */
    public function setClassNamingStrategy(string $classNamingStrategy = null): void
    {
        $this->classNamingStrategy = ($classNamingStrategy !== null) ? $classNamingStrategy : self::OPTION_CLASS_NAMING_DEFAULT;
        $this->checkNamingStrategy($this->classNamingStrategy, self::OPTION_CLASS_NAMING);
    }

    /**
     * @return string
     */
    public function getAttributeNamingStrategy(): string
    {
        return $this->attributeNamingStrategy;
    }

    /**
     * @return NamingStrategy
     */
    public function getAttributeNamingInstance(): NamingStrategy
    {
        $className = $this->getAttributeNamingStrategy();
        return new $className;
    }

    /**
     * @param string|null $attributeNamingStrategy
     *
     * @throws ReflectionException
     */
    public function setAttributeNamingStrategy(string $attributeNamingStrategy = null): void
    {
        $this->attributeNamingStrategy = ($attributeNamingStrategy !== null) ? $attributeNamingStrategy : self::OPTION_ATTRIBUTE_NAMING_DEFAULT;
        $this->checkNamingStrategy($this->attributeNamingStrategy, self::OPTION_ATTRIBUTE_NAMING);
    }

    /**
     * @return string|null
     */
    public function getDefaultNamespace(): ?string
    {
        return $this->defaultNamespace;
    }

    /**
     * @param string|null $defaultNamespace
     */
    public function setDefaultNamespace(string $defaultNamespace = null): void
    {
        $this->defaultNamespace = $defaultNamespace;
    }

    /**
     * @return string|null
     */
    public function getConnectionName(): ?string
    {
        return $this->connectionName;
    }

    /**
     * @param string|null $connectionName
     */
    public function setConnectionName(string $connectionName = null): void
    {
        $this->connectionName = $connectionName;
    }

    /**
     * @param string $className
     * @param string $parameterName
     * @throws ReflectionException
     */
    protected function checkNamingStrategy(string $className, string $parameterName): void
    {
        if (!ClassUtil::exists($className)) {
            $error = sprintf(self::ERROR_NAMING_CLASS_DOES_NOT_EXIST, $parameterName, $className);
            throw new InvalidConfigurationException($error);
        }

        if (!ClassUtil::implementsInterface($className, self::NAMING_INTERFACE)) {
            $error = sprintf(self::ERROR_NAMING_STRATEGY_DOES_NOT_IMPLEMENT, $parameterName, $className, self::NAMING_INTERFACE);
            throw new InvalidConfigurationException($error);
        }
    }

}