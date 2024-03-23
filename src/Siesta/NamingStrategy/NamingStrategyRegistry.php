<?php

namespace Siesta\NamingStrategy;

use Siesta\Config\MainGeneratorConfig;
use Siesta\Config\ReverseConfig;

class NamingStrategyRegistry
{

    /**
     * @var NamingStrategy|null
     */
    protected static ?NamingStrategy $attributeNamingStrategy = null;

    /**
     * @var NamingStrategy|null
     */
    protected static ?NamingStrategy $columnNamingStrategy = null;

    /**
     * @var NamingStrategy|null
     */
    protected static ?NamingStrategy $tableNamingStrategy = null;

    /**
     * @var NamingStrategy|null
     */
    protected static ?NamingStrategy $classNamingStrategy = null;

    /**
     * @return NamingStrategy
     */
    public static function getClassNamingStrategy(): NamingStrategy
    {
        if (self::$classNamingStrategy === null) {
            $classNamingDefault = ReverseConfig::OPTION_CLASS_NAMING_DEFAULT;
            self::$classNamingStrategy = new $classNamingDefault;
        }
        return self::$classNamingStrategy;
    }

    /**
     * @param NamingStrategy $classNamingStrategy
     */
    public static function setClassNamingStrategy(NamingStrategy $classNamingStrategy): void
    {
        self::$classNamingStrategy = $classNamingStrategy;
    }

    /**
     * @return NamingStrategy
     */
    public static function getAttributeNamingStrategy(): NamingStrategy
    {
        if (self::$attributeNamingStrategy === null) {
            $attributeNamingDefault = ReverseConfig::OPTION_ATTRIBUTE_NAMING_DEFAULT;
            self::$attributeNamingStrategy = new $attributeNamingDefault;
        }
        return self::$attributeNamingStrategy;
    }

    /**
     * @param NamingStrategy $attributeNamingStrategy
     */
    public static function setAttributeNamingStrategy(NamingStrategy $attributeNamingStrategy): void
    {
        self::$attributeNamingStrategy = $attributeNamingStrategy;
    }

    /**
     * @return NamingStrategy
     */
    public static function getTableNamingStrategy(): NamingStrategy
    {
        if (self::$tableNamingStrategy === null) {
            $strategy = MainGeneratorConfig::OPTION_TABLE_NAMING_STRATEGY_DEFAULT;
            self::$tableNamingStrategy = new $strategy;
        }

        return self::$tableNamingStrategy;
    }

    /**
     * @param NamingStrategy $tableNamingStrategy
     */
    public static function setTableNamingStrategy(NamingStrategy $tableNamingStrategy): void
    {
        self::$tableNamingStrategy = $tableNamingStrategy;
    }

    /**
     * @return NamingStrategy
     */
    public static function getColumnNamingStrategy(): NamingStrategy
    {
        if (self::$columnNamingStrategy === null) {
            $strategy = MainGeneratorConfig::OPTION_COLUMN_NAMING_STRATEGY_DEFAULT;
            self::$columnNamingStrategy = new $strategy;
        }
        return self::$columnNamingStrategy;
    }

    /**
     * @param NamingStrategy $columnNamingStrategy
     */
    public static function setColumnNamingStrategy(NamingStrategy $columnNamingStrategy): void
    {
        self::$columnNamingStrategy = $columnNamingStrategy;
    }

}