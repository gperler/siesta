<?php

declare(strict_types=1);

namespace SiestaTest\Functional\Generator;

use PHPUnit\Framework\TestCase;
use Siesta\Config\GenericConfigLoader;
use Siesta\Generator\MainGenerator;
use Siesta\Logger\NullLogger;
use Siesta\Model\DataModel;
use Siesta\Model\ValidationLogger;
use Siesta\Util\File;
use Siesta\XML\XMLReader;

/**
 * Generates an entity whose "secret" attribute is marked encrypted="true" and asserts the
 * generated code encrypts on save and decrypts on read, while an ordinary string attribute still
 * uses the plain quoting/reading path.
 */
class GeneratedEncryptionTest extends TestCase
{

    private ?string $generatedCode = null;

    private function getGeneratedCode(): string
    {
        if ($this->generatedCode !== null) {
            return $this->generatedCode;
        }

        $logger = new ValidationLogger();
        $logger->setLogger(new NullLogger());

        $xmlReader = new XMLReader(new File(__DIR__ . "/schema/encrypted.test.xml"));
        $dataModel = new DataModel();
        $dataModel->addXMLEntityList($xmlReader->getEntityList());
        $dataModel->update();

        $configLoader = new GenericConfigLoader();
        $configList = $configLoader->loadAndValidate($logger);

        $targetDir = sys_get_temp_dir() . "/siesta_gen_" . getmypid();
        @mkdir($targetDir, 0777, true);

        $main = new MainGenerator();
        $main->setup($configList);
        $main->generate($dataModel, $targetDir);

        $this->generatedCode = file_get_contents($targetDir . "/Person.php");

        foreach (glob($targetDir . "/*") as $file) {
            unlink($file);
        }
        rmdir($targetDir);

        return $this->generatedCode;
    }

    public function testEncryptedAttributeEncryptsOnSave(): void
    {
        $code = $this->getGeneratedCode();
        // encrypted attribute goes through the connection, which encrypts and quotes in one step
        $this->assertStringContainsString('$connection->escapeAndEncrypt($this->secret)', $code);
    }

    public function testPlainAttributeStillUsesQuoteStringOnSave(): void
    {
        $code = $this->getGeneratedCode();
        $this->assertStringContainsString('Escaper::quoteString($connection, $this->name', $code);
        $this->assertStringNotContainsString('escapeAndEncrypt($this->name', $code);
    }

    public function testEncryptedAttributeDecryptsOnRead(): void
    {
        $code = $this->getGeneratedCode();
        $this->assertStringContainsString('$this->secret = $resultSet->getDecryptedValue("secret");', $code);
    }

    public function testPlainAttributeStillUsesGetStringValueOnRead(): void
    {
        $code = $this->getGeneratedCode();
        $this->assertStringContainsString('$this->name = $resultSet->getStringValue("name");', $code);
    }

}
