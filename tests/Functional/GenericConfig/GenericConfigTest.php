<?php

namespace SiestaTest\Functional\GenericConfig;

use Codeception\Test\Unit;
use Siesta\Config\GenericConfigLoader;
use Siesta\Config\GenericGeneratorConfig;
use Siesta\Model\ValidationLogger;
use Siesta\Util\File;
use SiestaTest\TestUtil\CodeceptionLogger;

class GenericConfigTest extends Unit
{

    public function testConfigValidation1()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/generic.config.test.json"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(4, $validationLogger->getErrorCount());
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_HAS_NO_NAME_CODE));
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_CLASS_DOES_NOT_EXIST_CODE));
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_PLUGIN_DOES_NOT_EXIST_CODE));
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_VALIDATOR_DOES_NOT_EXIST_CODE));

    }

    public function testConfigValidation2()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/generic.config.test.no.array.json"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(2, $validationLogger->getErrorCount());
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_PLUGIN_IS_NOT_ARRAY_CODE));
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_VALIDATOR_IS_NOT_ARRAY_CODE));

    }

    public function testClassDoesNotImplement()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/generic.config.test.not.implements.json"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(3, $validationLogger->getErrorCount());

        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_CLASS_DOES_NOT_IMPLEMENT_CODE));
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_PLUGIN_DOES_NOT_IMPLEMENT_CODE));
        $this->assertTrue($validationLogger->hasErrorCode(GenericGeneratorConfig::ERROR_GENERATOR_VALIDATOR_DOES_NOT_IMPLEMENT_CODE));
    }

    public function testValues()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/generic.config.test.values.json"));
        $genericConfigList = $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(0, $validationLogger->getErrorCount());
        $this->assertNotNull($genericConfigList);

        $this->assertSame(1, sizeof($genericConfigList));

        $genericConfig = $genericConfigList[0];

        $this->assertSame("name", $genericConfig->getName());
        $this->assertSame("Siesta\\Generator\\EntityGenerator", $genericConfig->getClassName());

        $pluginList = $genericConfig->getPluginList();
        $this->assertNotNull($pluginList);
        $this->assertSame(1, sizeof($pluginList));
        $this->assertSame("Siesta\\GeneratorPlugin\\Entity\\ConstantPlugin", $pluginList[0]);

        $validatorList = $genericConfig->getValidatorList();
        $this->assertNotNull($validatorList);
        $this->assertSame(1, sizeof($validatorList));
        $this->assertSame("Siesta\\Validator\\DefaultAttributeValidator", $validatorList[0]);

    }

}