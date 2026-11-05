<?php

declare(strict_types=1);

namespace SiestaTest\Functional\Model;

use PHPUnit\Framework\TestCase;
use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;

class AttributeNetLengthTest extends TestCase
{

    private function attribute(string $dbType, bool $encrypted): Attribute
    {
        $entity = new Entity(new DataModel());
        $attribute = $entity->newAttribute();
        $attribute->setPhpType("string");
        $attribute->setDbType($dbType);
        $attribute->setIsEncrypted($encrypted);
        return $attribute;
    }

    public function testPlainAttributeReturnsColumnLength(): void
    {
        $this->assertSame(100, $this->attribute("VARCHAR(100)", false)->getNetLength());
    }

    public function testEncryptedAttributeReturnsReducedLength(): void
    {
        $this->assertSame(29, $this->attribute("VARCHAR(100)", true)->getNetLength());
    }

    public function testUnboundedColumnHasNoLimit(): void
    {
        $this->assertNull($this->attribute("TEXT", true)->getNetLength());
        $this->assertNull($this->attribute("INT", false)->getNetLength());
    }

}
