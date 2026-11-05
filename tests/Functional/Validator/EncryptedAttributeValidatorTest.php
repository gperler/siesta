<?php

declare(strict_types=1);

namespace SiestaTest\Functional\Validator;

use PHPUnit\Framework\TestCase;
use Siesta\Config\GenericConfigLoader;
use Siesta\Logger\NullLogger;
use Siesta\Model\DataModel;
use Siesta\Model\ValidationLogger;
use Siesta\Util\File;
use Siesta\Validator\DefaultAttributeValidator;
use Siesta\Validator\Validator;
use Siesta\XML\XMLReader;

class EncryptedAttributeValidatorTest extends TestCase
{

    private function validate(string $schemaFile): ValidationLogger
    {
        $logger = new ValidationLogger();
        $logger->setLogger(new NullLogger());

        $xmlReader = new XMLReader(new File(__DIR__ . "/data/" . $schemaFile));
        $dataModel = new DataModel();
        $dataModel->addXMLEntityList($xmlReader->getEntityList());
        $dataModel->update();

        $configLoader = new GenericConfigLoader();
        $configList = $configLoader->loadAndValidate($logger);

        $validator = new Validator();
        $validator->setup($configList);
        $validator->validateDataModel($dataModel, $logger);

        return $logger;
    }

    public function testEncryptedGuardrails(): void
    {
        $logger = $this->validate("validate.encrypted.test.xml");
        $errorCodeList = $logger->getErrorCodeList();

        // encInt is encrypted but not a string
        $this->assertContains(DefaultAttributeValidator::ERROR_ENCRYPTED_NOT_STRING_CODE, $errorCodeList);
        // EncKey.code is encrypted but is a primary key
        $this->assertContains(DefaultAttributeValidator::ERROR_ENCRYPTED_KEY_CODE, $errorCodeList);
        // encVarchar / code are encrypted on a bounded column -> truncation warning
        $this->assertTrue($logger->hasWarningCode(DefaultAttributeValidator::WARN_ENCRYPTED_LENGTH_CODE));
    }

}
