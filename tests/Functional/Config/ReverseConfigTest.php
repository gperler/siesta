<?php

namespace SiestaTest\Functional\Config;

use Codeception\Test\Unit;
use Siesta\Config\ReverseConfig;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Util\ArrayUtil;

class ReverseConfigTest extends Unit
{

    public function testDefaultValues()
    {
        $reverseConfig = new ReverseConfig();

        $this->assertSame(null, $reverseConfig->getConnectionName());
        $this->assertSame(ReverseConfig::OPTION_CLASS_NAMING_DEFAULT, $reverseConfig->getClassNamingStrategy());
        $this->assertSame(ReverseConfig::OPTION_ATTRIBUTE_NAMING_DEFAULT, $reverseConfig->getAttributeNamingStrategy());
        $this->assertSame(null, $reverseConfig->getDefaultNamespace());
        $this->assertSame(ReverseConfig::OPTION_ENTITY_FILE_SUFFIX_DEFAULT, $reverseConfig->getEntityXMLFileSuffix());
        $this->assertSame(ReverseConfig::OPTION_TARGET_FILE_DEFAULT, $reverseConfig->getTargetFile());
        $this->assertSame(ReverseConfig::OPTION_TARGET_PATH_DEFAULT, $reverseConfig->getTargetPath());
        $this->assertSame(ReverseConfig::OPTION_SINGLE_FILE_DEFAULT, $reverseConfig->isSingleFile());

        $this->assertNotNull($reverseConfig->getClassNamingInstance());
        $this->assertNotNull($reverseConfig->getAttributeNamingInstance());


        $arrayValues = $reverseConfig->toArray();

        $this->assertSame(null, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_CONNECTION_NAME));
        $this->assertSame(ReverseConfig::OPTION_CLASS_NAMING_DEFAULT, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_CLASS_NAMING));
        $this->assertSame(ReverseConfig::OPTION_ATTRIBUTE_NAMING_DEFAULT, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_ATTRIBUTE_NAMING));
        $this->assertSame(null, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_DEFAULT_NAMESPACE));
        $this->assertSame(ReverseConfig::OPTION_ENTITY_FILE_SUFFIX_DEFAULT, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_ENTITY_FILE_SUFFIX));
        $this->assertSame(ReverseConfig::OPTION_TARGET_FILE_DEFAULT, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_TARGET_FILE));
        $this->assertSame(ReverseConfig::OPTION_TARGET_PATH_DEFAULT, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_TARGET_PATH));
        $this->assertSame(ReverseConfig::OPTION_SINGLE_FILE_DEFAULT, ArrayUtil::getFromArray($arrayValues, ReverseConfig::OPTION_SINGLE_FILE));

    }

    public function testClassNamingExists()
    {
        try {
            $reverseConfig = new ReverseConfig([
                ReverseConfig::OPTION_CLASS_NAMING => "test"
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }

    public function testClassNamingImplements()
    {
        try {
            $reverseConfig = new ReverseConfig([
                ReverseConfig::OPTION_CLASS_NAMING => 'SiestaTest\Functional\Config\ReverseConfigTest'
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }

    public function testAttributeNamingExists()
    {
        try {
            $reverseConfig = new ReverseConfig([
                ReverseConfig::OPTION_ATTRIBUTE_NAMING => "test"
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }

    public function testAttributeNamingImplements()
    {
        try {
            $reverseConfig = new ReverseConfig([
                ReverseConfig::OPTION_ATTRIBUTE_NAMING => 'SiestaTest\Functional\Config\ReverseConfigTest'
            ]);
            $this->assertTrue(false);
        } catch (InvalidConfigurationException $e) {

        }
    }
}