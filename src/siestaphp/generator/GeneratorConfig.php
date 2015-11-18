<?php

namespace siestaphp\generator;

use siestaphp\util\Util;

/**
 * Class GeneratorConfig
 * @package siestaphp\generator
 */
class GeneratorConfig
{

    const OPTION_DROP_UNUSED_TABLES = "dropUnusedTables";

    const OPTION_ENTITY_FILE_SUFFX = "entityFileSuffix";

    const OPTION_BASE_DIR = "baseDir";

    const OPTION_CONNECTION_NAME = "connectionName";

    const OPTION_MIGRATION_METHOD = "migrationMethod";

    const OPTION_MIGRATION_TARGET_PATH = "migrationTargetPath";

    public static $OPTION_LIST = [self::OPTION_DROP_UNUSED_TABLES, self::OPTION_ENTITY_FILE_SUFFX, self::OPTION_BASE_DIR, self::OPTION_CONNECTION_NAME, self::OPTION_MIGRATION_METHOD, self::OPTION_MIGRATION_TARGET_PATH];

    const DEFAULT_MIGRATION_TARGET = "/migration";

    const DEFAULT_ENTITY_SUFFIX = "entity.xml";

    const MIGRATION_DIRECT_EXECUTION = "direct";

    const MIGRATOR_CREATE_SQL_FILE = "sql";

    const MIGRATOR_CREATE_PHP_FILE = "php";

    private static $ALLOWED_VALUES = [self::MIGRATION_DIRECT_EXECUTION, self::MIGRATOR_CREATE_PHP_FILE, self::MIGRATOR_CREATE_SQL_FILE];

    /**
     * @var string
     */
    protected $baseDir;

    /**
     * @var string
     */
    protected $entityFileSuffix;

    /**
     * @var bool
     */
    protected $dropUnusedTables;

    /**
     * @var string
     */
    protected $connectionName;

    /**
     * @var string
     */
    protected $migrationTargetPath;

    /**
     * @var string
     */
    protected $migrationMethod;

    /**
     * @param array $values
     */
    public function __construct($values = null)
    {
        $this->setBaseDir(Util::getFromArray($values, self::OPTION_BASE_DIR));
        $this->setConnectionName(Util::getFromArray($values, self::OPTION_CONNECTION_NAME));
        $this->setDropUnusedTables(Util::getFromArray($values, self::OPTION_DROP_UNUSED_TABLES));
        $this->setEntityFileSuffix(Util::getFromArray($values, self::OPTION_ENTITY_FILE_SUFFX));
        $this->setMigrationMethod(Util::getFromArray($values, self::OPTION_MIGRATION_METHOD));
        $this->setMigrationTargetPath(Util::getFromArray($values, self::OPTION_MIGRATION_TARGET_PATH));
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setValue($key, $value)
    {
        switch ($key) {
            case self::OPTION_BASE_DIR:
                $this->setBaseDir($value);
                break;

            case self::OPTION_CONNECTION_NAME:
                $this->setConnectionName($value);
                break;

            case self::OPTION_DROP_UNUSED_TABLES:
                $this->setDropUnusedTables($value);
                break;

            case self::OPTION_ENTITY_FILE_SUFFX:
                $this->setEntityFileSuffix($value);
                break;

            case self::OPTION_MIGRATION_METHOD:
                $this->setMigrationMethod($value);
                break;

            case self::OPTION_MIGRATION_TARGET_PATH:
                $this->setMigrationTargetPath($value);
                break;
        }
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = ($baseDir) ? $baseDir : getcwd();
    }

    /**
     * @return string
     */
    public function getEntityFileSuffix()
    {
        return $this->entityFileSuffix;
    }

    /**
     * @param string $entityFileSuffix
     */
    public function setEntityFileSuffix($entityFileSuffix)
    {

        $this->entityFileSuffix = ($entityFileSuffix) ? $entityFileSuffix : self::DEFAULT_ENTITY_SUFFIX;
    }

    /**
     * @return boolean
     */
    public function isDropUnusedTables()
    {
        return $this->dropUnusedTables;
    }

    /**
     * @param boolean $dropUnusedTables
     */
    public function setDropUnusedTables($dropUnusedTables)
    {
        $this->dropUnusedTables = $dropUnusedTables;
    }

    /**
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connectionName;
    }

    /**
     * @param string $connectionName
     */
    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
    }

    /**
     * @return string
     */
    public function getMigrationTargetPath()
    {
        return $this->migrationTargetPath;
    }

    /**
     * @param string $migrationTargetPath
     */
    public function setMigrationTargetPath($migrationTargetPath)
    {
        $this->migrationTargetPath = ($migrationTargetPath) ? $migrationTargetPath : getcwd() . self::DEFAULT_MIGRATION_TARGET;
    }

    /**
     * @return string
     */
    public function getMigrationMethod()
    {
        return $this->migrationMethod;
    }

    /**
     * @param string $migrationMethod
     */
    public function setMigrationMethod($migrationMethod)
    {
        if (in_array($migrationMethod, self::$ALLOWED_VALUES)) {
            $this->migrationMethod = $migrationMethod;
            return;
        }
        $this->migrationMethod = self::MIGRATION_DIRECT_EXECUTION;
    }

}