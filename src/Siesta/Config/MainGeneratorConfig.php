<?php

namespace Siesta\Config;

use ReflectionException;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\NamingStrategy\NamingStrategy;
use Siesta\Util\ArrayUtil;
use Siesta\Util\ClassUtil;
use Siesta\Util\File;
use Siesta\Util\SiestaDateTime;


class MainGeneratorConfig
{

    const NAMING_INTERFACE = 'Siesta\NamingStrategy\NamingStrategy';

    const ERROR_NAMING_CLASS_DOES_NOT_EXIST = "%s class '%' does not exist or is not auto(loaded)";

    const ERROR_NAMING_CLASS_DOES_NOT_IMPLEMENT = "%s class '%s' does not implement interface '%s'";

    const ERROR_MIGRATION_METHOD_NOT_ALLOWED = "MigrationMethod '%s' not allowed. Allowed values are '%s";

    /* dropUnusedTables */
    const OPTION_DROP_UNUSED_TABLES = "dropUnusedTables";

    const OPTION_DROP_UNUSED_TABLES_DEFAULT = true;

    /* entityFileSuffix */
    const OPTION_ENTITY_FILE_SUFFIX = "entityFileSuffix";

    const OPTION_ENTITY_FILE_SUFFIX_DEFAULT = ".entity.xml";

    /* migrationTargetPath */
    const OPTION_MIGRATION_TARGET_PATH = "migrationTargetPath";

    const OPTION_MIGRATION_TARGET_PATH_DEFAULT = "migration";

    /* tableNamingStrategy */
    const OPTION_TABLE_NAMING_STRATEGY = "tableNamingStrategy";

    const OPTION_TABLE_NAMING_STRATEGY_DEFAULT = 'Siesta\NamingStrategy\ToUnderScoreStrategy';

    /* columnNamingStrategy */
    const OPTION_COLUMN_NAMING_STRATEGY = "columnNamingStrategy";

    const OPTION_COLUMN_NAMING_STRATEGY_DEFAULT = 'Siesta\NamingStrategy\ToUnderScoreStrategy';

    /* migrationMethod */
    const OPTION_MIGRATION_METHOD = "migrationMethod";

    const MIGRATION_METHOD_DIRECT_EXECUTION = "direct";

    const MIGRATION_METHOD_CREATE_SQL_FILE = "sql";

    const OPTION_MIGRATION_METHOD_DEFAULT = self::MIGRATION_METHOD_DIRECT_EXECUTION;

    const ALLOWED_MIGRATION_METHOD = [
        self::MIGRATION_METHOD_DIRECT_EXECUTION,
        self::MIGRATION_METHOD_CREATE_SQL_FILE
    ];

    /* baseDir */
    const OPTION_BASE_DIR = "baseDir";

    const OPTION_BASE_DIR_DEFAULT = "src";

    const OPTION_CONNECTION_NAME = "connectionName";

    const OPTION_GENERIC_GENERATOR_CONFIG_FILE = "genericGeneratorConfiguration";

    /**
     * @var bool
     */
    protected $dropUnusedTables;

    /**
     * @var string
     */
    protected $entityFileSuffix;

    /**
     * @var string
     */
    protected $migrationTargetPath;

    /**
     * @var string
     */
    protected $tableNamingStrategy;

    /**
     * @var string
     */
    protected $columnNamingStrategy;

    /**
     * @var string
     */
    protected $migrationMethod;

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $connectionName;

    /**
     * @var
     */
    protected $genericGeneratorConfiguration;

    /**
     * @param array|null $values
     * @throws ReflectionException
     */
    public function __construct(array $values = null)
    {
        $this->setDropUnusedTables(ArrayUtil::getFromArray($values, self::OPTION_DROP_UNUSED_TABLES));
        $this->setEntityFileSuffix(ArrayUtil::getFromArray($values, self::OPTION_ENTITY_FILE_SUFFIX));
        $this->setMigrationTargetPath(ArrayUtil::getFromArray($values, self::OPTION_MIGRATION_TARGET_PATH));
        $this->setTableNamingStrategy(ArrayUtil::getFromArray($values, self::OPTION_TABLE_NAMING_STRATEGY));
        $this->setColumnNamingStrategy(ArrayUtil::getFromArray($values, self::OPTION_COLUMN_NAMING_STRATEGY));

        $this->setMigrationMethod(ArrayUtil::getFromArray($values, self::OPTION_MIGRATION_METHOD));
        $this->setBaseDir(ArrayUtil::getFromArray($values, self::OPTION_BASE_DIR));
        $this->setConnectionName(ArrayUtil::getFromArray($values, self::OPTION_CONNECTION_NAME));
        $this->setGenericGeneratorConfiguration(ArrayUtil::getFromArray($values, self::OPTION_GENERIC_GENERATOR_CONFIG_FILE));
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::OPTION_DROP_UNUSED_TABLES => $this->isDropUnusedTables(),
            self::OPTION_ENTITY_FILE_SUFFIX => $this->getEntityFileSuffix(),
            self::OPTION_MIGRATION_TARGET_PATH => $this->getMigrationTargetPath(),
            self::OPTION_TABLE_NAMING_STRATEGY => $this->getTableNamingStrategy(),
            self::OPTION_COLUMN_NAMING_STRATEGY => $this->getColumnNamingStrategy(),
            self::OPTION_MIGRATION_METHOD => $this->getMigrationMethod(),
            self::OPTION_BASE_DIR => $this->getBaseDir(),
            self::OPTION_CONNECTION_NAME => $this->getConnectionName(),
            self::OPTION_GENERIC_GENERATOR_CONFIG_FILE => $this->getGenericGeneratorConfiguration()
        ];
    }

    /**
     * @return bool
     */
    public function isDropUnusedTables(): bool
    {
        return $this->dropUnusedTables;
    }

    /**
     * @param bool $dropUnusedTables
     */
    public function setDropUnusedTables(bool $dropUnusedTables = null): void
    {
        $this->dropUnusedTables = ($dropUnusedTables !== null) ? $dropUnusedTables : self::OPTION_DROP_UNUSED_TABLES_DEFAULT;
    }

    /**
     * @return string
     */
    public function getEntityFileSuffix(): string
    {
        return $this->entityFileSuffix;
    }

    /**
     * @param string|null $entityFileSuffix
     */
    public function setEntityFileSuffix(string $entityFileSuffix = null): void
    {

        $this->entityFileSuffix = ($entityFileSuffix !== null) ? $entityFileSuffix : self::OPTION_ENTITY_FILE_SUFFIX_DEFAULT;
    }

    /**
     * @return File
     */
    public function getMigrationFile(): File
    {
        $dateTime = new SiestaDateTime();
        $fileName = $this->getMigrationTargetPath() . "/migration_" . $dateTime->getSQLDateTime() . '.sql';
        $file = new File($fileName);
        $file->createDirForFile();
        return $file;
    }

    /**
     * @return string
     */
    public function getMigrationTargetPath(): string
    {
        return $this->migrationTargetPath;
    }

    /**
     * @param string|null $migrationTargetPath
     */
    public function setMigrationTargetPath(string $migrationTargetPath = null): void
    {
        $this->migrationTargetPath = ($migrationTargetPath !== null) ? $migrationTargetPath : self::OPTION_MIGRATION_TARGET_PATH_DEFAULT;
    }

    /**
     * @return string
     */
    public function getTableNamingStrategy(): string
    {
        return $this->tableNamingStrategy;
    }

    /**
     * @return NamingStrategy
     */
    public function getTableNamingStrategyInstance(): NamingStrategy
    {
        $className = $this->getTableNamingStrategy();
        return new $className;
    }

    /**
     * @param string|null $tableNamingStrategy
     * @throws ReflectionException
     */
    public function setTableNamingStrategy(string $tableNamingStrategy = null): void
    {
        $this->tableNamingStrategy = ($tableNamingStrategy !== null) ? $tableNamingStrategy : self::OPTION_TABLE_NAMING_STRATEGY_DEFAULT;
        $this->checkNamingStrategy($this->tableNamingStrategy, self::OPTION_COLUMN_NAMING_STRATEGY);
    }

    /**
     * @return string
     */
    public function getColumnNamingStrategy(): string
    {

        return $this->columnNamingStrategy;
    }

    /**
     * @return NamingStrategy
     * @throws InvalidConfigurationException
     */
    public function getColumnNamingStrategyInstance(): NamingStrategy
    {
        $className = $this->getColumnNamingStrategy();
        return new $className;
    }

    /**
     * @param string|null $columnNamingStrategy
     *
     * @throws ReflectionException
     */
    public function setColumnNamingStrategy(string $columnNamingStrategy = null): void
    {
        $this->columnNamingStrategy = ($columnNamingStrategy !== null) ? $columnNamingStrategy : self::OPTION_COLUMN_NAMING_STRATEGY_DEFAULT;
        $this->checkNamingStrategy($this->columnNamingStrategy, self::OPTION_COLUMN_NAMING_STRATEGY);
    }

    /**
     * @return string
     */
    public function getMigrationMethod(): string
    {
        return $this->migrationMethod;
    }

    /**
     * @return bool
     */
    public function isMigrateDirect(): bool
    {
        return $this->getMigrationMethod() === self::MIGRATION_METHOD_DIRECT_EXECUTION;
    }

    /**
     * @return bool
     */
    public function isMigrationToFile(): bool
    {
        return $this->getMigrationMethod() === self::MIGRATION_METHOD_CREATE_SQL_FILE;
    }

    /**
     * @param string|null $migrationMethod
     *
     */
    public function setMigrationMethod(string $migrationMethod = null): void
    {
        $this->migrationMethod = ($migrationMethod !== null) ? strtolower($migrationMethod) : self::OPTION_MIGRATION_METHOD_DEFAULT;
        if (!in_array($this->migrationMethod, self::ALLOWED_MIGRATION_METHOD)) {
            $allowed = implode(", ", self::ALLOWED_MIGRATION_METHOD);
            $error = sprintf(self::ERROR_MIGRATION_METHOD_NOT_ALLOWED, $this->migrationMethod, $allowed);
            throw new InvalidConfigurationException($error);
        }
    }

    /**
     * @return string
     */
    public function getBaseDir(): string
    {
        return $this->baseDir;
    }

    /**
     * @param string|null $baseDir
     */
    public function setBaseDir(string $baseDir = null): void
    {
        $this->baseDir = ($baseDir !== null) ? $baseDir : self::OPTION_BASE_DIR_DEFAULT;
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
     *
     */
    public function setConnectionName(string $connectionName = null): void
    {
        $this->connectionName = $connectionName;
    }

    /**
     * @return string|null
     */
    public function getGenericGeneratorConfiguration(): ?string
    {
        return $this->genericGeneratorConfiguration;
    }

    /**
     * @param string|null $genericGeneratorConfiguration
     */
    public function setGenericGeneratorConfiguration(string $genericGeneratorConfiguration = null): void
    {
        $this->genericGeneratorConfiguration = $genericGeneratorConfiguration;
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
            $error = sprintf(self::ERROR_NAMING_CLASS_DOES_NOT_IMPLEMENT, $parameterName, $className, self::NAMING_INTERFACE);
            throw new InvalidConfigurationException($error);
        }
    }
}