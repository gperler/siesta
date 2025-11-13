<?php

namespace SiestaTest\Functional\GenericConfig;

use Codeception\Test\Unit;
use Siesta\Config\GenericConfigLoader;
use Siesta\Model\ValidationLogger;
use Siesta\Util\File;
use SiestaTest\TestUtil\CodeceptionLogger;

class GenericConfigLoaderTest extends Unit
{

    public function testDefaultConfig()
    {

        $codeceptionLogger = new CodeceptionLogger(false);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->loadAndValidate($validationLogger);
        $this->assertSame(0, $validationLogger->getErrorCount());
    }

    public function testFileDoesNotExist()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/doesnotexist"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(1, $validationLogger->getErrorCount());
        $this->assertTrue($validationLogger->hasErrorCode(GenericConfigLoader::ERROR_CONFIG_DOES_NOT_EXIST_CODE));
    }

    public function testFileIsInvalid()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/invalid.json"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(1, $validationLogger->getErrorCount());
        $this->assertTrue($validationLogger->hasErrorCode(GenericConfigLoader::ERROR_CONFIG_FILE_IS_EMPTY_CODE));
    }

    public function testMissingGeneratorInvalid()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/missing.json"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(1, $validationLogger->getErrorCount());
        $this->assertTrue($validationLogger->hasErrorCode(GenericConfigLoader::ERROR_CONFIG_FILE_MISSES_GEN_CODE));
    }

    public function testNoGeneratorDefined()
    {
        $codeceptionLogger = new CodeceptionLogger(true);
        $validationLogger = new ValidationLogger();
        $validationLogger->setLogger($codeceptionLogger);

        $configLoader = new GenericConfigLoader();
        $configLoader->setConfigFile(new File(__DIR__ . "/data/nogenerator.json"));
        $configLoader->loadAndValidate($validationLogger);

        $this->assertSame(1, $validationLogger->getErrorCount());
        $this->assertTrue($validationLogger->hasErrorCode(GenericConfigLoader::ERROR_NO_GENERIC_GENERATORS_DEFINED_CODE));
    }


    //
}