<?php

namespace siestaphp\tests\unit\config;

use siestaphp\Config;
use siestaphp\exceptions\InvalidConfiguration;

/**
 * Class ConfigTest
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    // tests
    public function testConfigException()
    {
        Config::reset();
        try {
            $config = Config::getInstance(null);
            $this->assertTrue(false, "No Config Exception not found");

        } catch (InvalidConfiguration $e) {

        }
        Config::reset();
    }

    public function testFindConfigurationFileName()
    {
        Config::reset();
        $config = Config::getInstance("test.config.json");

        $this->assertNotNull($config, "config not found with name");

    }

    public function testFindRelativeFileName()
    {
        Config::reset();
        $config = Config::getInstance("tests/unit/config/test.config.json");
        $this->assertNotNull($config, "config not found with name");

    }

    public function testFindAbsoluteFileName()
    {
        Config::reset();
        $config = Config::getInstance(getcwd() . "/tests/unit/config/test.config.json");
        $this->assertNotNull($config, "config not found with name");

    }

    public function testOtherConfiguration()
    {
        Config::reset();
        $config = Config::getInstance("test.config.json");
        $this->assertNotNull($config, "config not found with name");

        $generatorConfig = $config->getGeneratorConfig();
        $this->assertNotNull($generatorConfig);
        $this->assertSame("baseDir", $generatorConfig->getBaseDir(), "basedir not correct");
        $this->assertSame("connectionName", $generatorConfig->getConnectionName(), "connectionName not correct");
        $this->assertSame("entityFileSuffix", $generatorConfig->getEntityFileSuffix(), "entityFileSuffix not correct");
        $this->assertSame("direct", $generatorConfig->getMigrationMethod(), "method not correct");
        $this->assertSame("migrationTargetPath", $generatorConfig->getMigrationTargetPath(), "migrationTargetPath not correct");

        $reverseConfig = $config->getReverseGeneratorConfig();
        $this->assertNotNull($reverseConfig);
        $this->assertSame("entityFileSuffix", $reverseConfig->getEntityFileSuffix(), "entityFileSuffix not correct");
        $this->assertSame("connectionName", $reverseConfig->getConnectionName(), "connectionName not correct");
        $this->assertSame("targetNamespace", $reverseConfig->getTargetNamespace(), "targetNamespace not correct");
        $this->assertSame("targetPath", $reverseConfig->getTargetPath(), "targetPath not correct");

    }
}
