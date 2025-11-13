<?php

namespace SiestaTest\Functional\Config;

use Codeception\Test\Unit;
use Siesta\Config\MainGeneratorConfig;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Util\ArrayUtil;

class MainGeneratorConfigTest extends Unit
{

    public function testDefaultValues()
    {
        $mainConfig = new MainGeneratorConfig();
        $this->assertSame(MainGeneratorConfig::OPTION_DROP_UNUSED_TABLES_DEFAULT, $mainConfig->isDropUnusedTables());
        $this->assertSame(MainGeneratorConfig::OPTION_ENTITY_FILE_SUFFIX_DEFAULT, $mainConfig->getEntityFileSuffix());
        $this->assertSame(MainGeneratorConfig::OPTION_MIGRATION_TARGET_PATH_DEFAULT, $mainConfig->getMigrationTargetPath());
        $this->assertSame(MainGeneratorConfig::OPTION_TABLE_NAMING_STRATEGY_DEFAULT, $mainConfig->getTableNamingStrategy());
        $this->assertSame(MainGeneratorConfig::OPTION_COLUMN_NAMING_STRATEGY_DEFAULT, $mainConfig->getColumnNamingStrategy());

        $this->assertSame(MainGeneratorConfig::OPTION_MIGRATION_METHOD_DEFAULT, $mainConfig->getMigrationMethod());
        $this->assertSame(MainGeneratorConfig::OPTION_BASE_DIR_DEFAULT, $mainConfig->getBaseDir());
        $this->assertSame(null, $mainConfig->getConnectionName());
        $this->assertSame(null, $mainConfig->getGenericGeneratorConfiguration());

        $this->assertTrue($mainConfig->isMigrateDirect());
        $this->assertFalse($mainConfig->isMigrationToFile());
        $this->assertNotNull($mainConfig->getTableNamingStrategyInstance());
        $this->assertNotNull($mainConfig->getColumnNamingStrategy());


        $arrayValues = $mainConfig->toArray();
        $this->assertSame(MainGeneratorConfig::OPTION_DROP_UNUSED_TABLES_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_DROP_UNUSED_TABLES));
        $this->assertSame(MainGeneratorConfig::OPTION_ENTITY_FILE_SUFFIX_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_ENTITY_FILE_SUFFIX));
        $this->assertSame(MainGeneratorConfig::OPTION_MIGRATION_TARGET_PATH_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_MIGRATION_TARGET_PATH));
        $this->assertSame(MainGeneratorConfig::OPTION_TABLE_NAMING_STRATEGY_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_TABLE_NAMING_STRATEGY));
        $this->assertSame(MainGeneratorConfig::OPTION_COLUMN_NAMING_STRATEGY_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_COLUMN_NAMING_STRATEGY));

        $this->assertSame(MainGeneratorConfig::OPTION_MIGRATION_METHOD_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_MIGRATION_METHOD));
        $this->assertSame(MainGeneratorConfig::OPTION_BASE_DIR_DEFAULT, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_BASE_DIR));
        $this->assertSame(null, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_CONNECTION_NAME));
        $this->assertSame(null, ArrayUtil::getFromArray($arrayValues, MainGeneratorConfig::OPTION_GENERIC_GENERATOR_CONFIG_FILE));

    }


    public function testTableNamingExists()
    {
        try {
            $reverseConfig = new MainGeneratorConfig([
                MainGeneratorConfig::OPTION_TABLE_NAMING_STRATEGY => "test"
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }

    public function testTableNamingImplements()
    {
        try {
            $reverseConfig = new MainGeneratorConfig([
                MainGeneratorConfig::OPTION_TABLE_NAMING_STRATEGY => 'SiestaTest\Functional\Config\ReverseConfigTest'
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }


    public function testColumnNamingExists()
    {
        try {
            $reverseConfig = new MainGeneratorConfig([
                MainGeneratorConfig::OPTION_COLUMN_NAMING_STRATEGY => "test"
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }

    public function testColumnNamingImplements()
    {
        try {
            $reverseConfig = new MainGeneratorConfig([
                MainGeneratorConfig::OPTION_COLUMN_NAMING_STRATEGY => 'SiestaTest\Functional\Config\ReverseConfigTest'
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }

}