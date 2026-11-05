<?php

namespace SiestaTest\End2End\Attribute;

use Codeception\Util\Debug;
use Siesta\Util\ArrayUtil;
use Siesta\Util\File;
use SiestaTest\End2End\Attribute\Generated\E2EAttribute;
use SiestaTest\End2End\Util\End2EndTest;

class AttributeTest extends End2EndTest
{

    public function setUp(): void
    {
        $silent = false;
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/attribute.test.xml");
        $this->generateSchema($schemaFile, __DIR__, $silent);
    }


    public function testX()
    {
    }


    public function testDefaultValues()
    {
        $attribute = new E2EAttribute();
        $this->assertSame(true, $attribute->getBool());
        $this->assertSame(42, $attribute->getInt());
        $this->assertSame(42.42, $attribute->getFloat());
        $this->assertSame("Discovery", $attribute->getString());

        $this->assertNotNull($attribute->getDateTime());
        $this->assertSame('1977-08-19 10:10:10', $attribute->getDateTime()->getSQLDateTime());

        $this->assertNotNull($attribute->getPDate());
        $this->assertSame('1977-08-19 00:00:00', $attribute->getPDate()->getSQLDateTime());

        $this->assertNotNull($attribute->getPTime());
        $this->assertSame("10:11:12", $attribute->getPTime()->getSQLTime());

        $this->assertNotNull($attribute->getObject());

        $this->assertSame("y", $attribute->getFromArray("x"));
    }


    public function testAutoincrement()
    {
        $attribute = new E2EAttribute();
        $this->assertSame(1, $attribute->getId(true));

        $attribute = new E2EAttribute();
        $this->assertSame(2, $attribute->getId(true));

        $attribute = new E2EAttribute();
        $this->assertSame(3, $attribute->getId(true));
    }


    public function testSave()
    {
        $attribute = new E2EAttribute();
        $attribute->getObject()->setX(77);
        $attribute->getObject()->setY(19);
        $attribute->addToArray("test", 123);
        $attribute->save();

        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EAttribute");

        $this->assertTrue($resultSet->hasNext());

        $this->assertSame(1, $resultSet->getIntegerValue("ID"));
        $this->assertSame(true, $resultSet->getBooleanValue("D_BOOLEAN"));
        $this->assertSame(42, $resultSet->getIntegerValue("D_INTEGER"));
        $this->assertSame(42.42, $resultSet->getFloatValue("D_FLOAT"));
        $this->assertSame("Discovery", $resultSet->getStringValue("D_STRING"));

        $dateTime = $resultSet->getDateTime("D_DATETIME");
        $this->assertNotNull($dateTime);
        $this->assertSame('1977-08-19 10:10:10', $dateTime->getSQLDateTime());

        $date = $resultSet->getDateTime("D_DATE");
        $this->assertNotNull($date);
        $this->assertSame('1977-08-19', $date->getSQLDate());

        $time = $resultSet->getDateTime("D_TIME");
        $this->assertNotNull($time);
        $this->assertSame("10:11:12", $time->getSQLTime());

        $object = $resultSet->getArray("object");
        $this->assertNotNull($object);
        $this->assertSame(77, $object["x"]);
        $this->assertSame(19, $object["y"]);

        $array = $resultSet->getArray("array");
        $this->assertNotNull($array);
        $arrayValue = ArrayUtil::getFromArray($array, "test");
        $this->assertSame(123, $arrayValue);
    }


    public function testNull()
    {
        $attribute = new E2EAttribute();
        $attribute->setBool(null);
        $attribute->setInt(null);
        $attribute->setFloat(null);
        $attribute->setString(null);
        $attribute->setDateTime(null);
        $attribute->setPDate(null);
        $attribute->setPTime(null);
        $attribute->save();

        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EAttribute");

        $this->assertTrue($resultSet->hasNext());

        $this->assertSame(1, $resultSet->getIntegerValue("ID"));
        $this->assertNull($resultSet->getBooleanValue("D_BOOLEAN"));
        $this->assertNull($resultSet->getIntegerValue("D_INTEGER"));
        $this->assertNull($resultSet->getFloatValue("D_FLOAT"));
        $this->assertNull($resultSet->getStringValue("D_STRING"));
        $this->assertNull($resultSet->getDateTime("D_DATETIME"));
        $this->assertNull($resultSet->getDateTime("D_DATE"));
        $this->assertNull($resultSet->getDateTime("D_TIME"));
    }


    public function testDelete()
    {
        $attribute = new E2EAttribute();
        $attribute->save();
        $connection = $this->getConnection();

        $resultSet = $connection->query("SELECT * FROM E2EAttribute");
        $this->assertTrue($resultSet->hasNext());
        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();

        $attribute->delete();

        $resultSet = $connection->query("SELECT * FROM E2EAttribute");
        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();
    }


    public function testFromResultSet()
    {
        $attribute = new E2EAttribute();
        $attribute->getObject()->setX(77);
        $attribute->getObject()->setY(19);
        $attribute->addToArray("LDX", "0x2A, BF");
        $attribute->save();

        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EAttribute");

        $this->assertTrue($resultSet->hasNext());

        $attributeReloaded = new E2EAttribute();
        $attributeReloaded->fromResultSet($resultSet);

        $this->assertFalse($resultSet->hasNext());
        $resultSet->close();

        $this->assertNotNull($attributeReloaded);

        $this->assertSame(1, $attributeReloaded->getId());
        $this->assertSame(true, $attributeReloaded->getBool());
        $this->assertSame(42, $attribute->getInt());
        $this->assertSame(42.42, $attribute->getFloat());
        $this->assertSame("Discovery", $attribute->getString());

        $this->assertNotNull($attributeReloaded->getDateTime());
        $this->assertSame('1977-08-19 10:10:10', $attributeReloaded->getDateTime()->getSQLDateTime());

        $this->assertNotNull($attributeReloaded->getPDate());
        $this->assertSame('1977-08-19', $attributeReloaded->getPDate()->getSQLDate());

        $this->assertNotNull($attributeReloaded->getPTime());
        $this->assertSame("10:11:12", $attributeReloaded->getPTime()->getSQLTime());

        $this->assertNotNull($attributeReloaded->getObject());
        $this->assertSame(77, $attributeReloaded->getObject()->getX());
        $this->assertSame(19, $attributeReloaded->getObject()->getY());

        $this->assertSame("0x2A, BF", $attributeReloaded->getFromArray("LDX"));
        $this->assertSame("y", $attributeReloaded->getFromArray("x"));
    }


    public function testJSONAndArray()
    {
        $file = new File(__DIR__ . "/schema/attribute.json");
        $jsonData = $file->getContents();

        $this->assertNotNull($jsonData);

        $attribute = new E2EAttribute();
        $attribute->fromJSON($jsonData);

        $this->assertSame(1, $attribute->getId());
        $this->assertSame(true, $attribute->getBool());
        $this->assertSame(7, $attribute->getInt());
        $this->assertSame(19.4, $attribute->getFloat());
        $this->assertSame("json_string", $attribute->getString());
        $this->assertSame("json_secret", $attribute->getSecret());
        $this->assertSame("not_for_db", $attribute->getTransient());

        $dateTime = $attribute->getDateTime();
        $this->assertNotNull($dateTime);
        $this->assertSame("2016-07-13T11:41:12", $dateTime->getJSONDateTime());

        $date = $attribute->getPDate();
        $this->assertNotNull($date);
        $this->assertSame("2016-07-13T00:00:00", $date->getJSONDateTime());

        $time = $attribute->getPTime();
        $this->assertNotNull($time);
        $this->assertSame("11:42:12", $time->getSQLTime());

        $object = $attribute->getObject();
        $this->assertNotNull($object);
        $this->assertSame(2001, $object->getX());
        $this->assertSame(3800, $object->getY());

        // test back to json
        $attributeJSON = $attribute->toJSON();

        $fileJSON = json_encode($file->loadAsJSONArray());
        $this->assertSame($fileJSON, $attributeJSON);
    }


    public function testEncryptedAttribute()
    {
        $attribute = new E2EAttribute();
        $attribute->setSecret("my-secret-value");
        $attribute->save();

        $connection = $this->getConnection();

        // at rest the column holds ciphertext - getStringValue does NOT decrypt
        $raw = $connection->query("SELECT D_SECRET FROM E2EAttribute");
        $this->assertTrue($raw->hasNext());
        $stored = $raw->getStringValue("D_SECRET");
        $raw->close();
        $this->assertStringStartsWith("enc:x:", $stored);
        $this->assertStringNotContainsString("my-secret-value", $stored);

        // reloading through the entity transparently decrypts
        $resultSet = $connection->query("SELECT * FROM E2EAttribute");
        $this->assertTrue($resultSet->hasNext());
        $reloaded = new E2EAttribute();
        $reloaded->fromResultSet($resultSet);
        $resultSet->close();
        $this->assertSame("my-secret-value", $reloaded->getSecret());
    }


    public function testEncryptedAttributeNull()
    {
        $attribute = new E2EAttribute();
        $attribute->setSecret(null);
        $attribute->save();

        $connection = $this->getConnection();
        $resultSet = $connection->query("SELECT * FROM E2EAttribute");
        $this->assertTrue($resultSet->hasNext());
        $this->assertNull($resultSet->getDecryptedValue("D_SECRET"));
    }


    public function testEncryptedAttributeMigration()
    {
        $connection = $this->getConnection();

        // a row written before encryption was enabled: plaintext, no enc: prefix
        $attribute = new E2EAttribute();
        $attribute->save();
        $connection->execute("UPDATE E2EAttribute SET D_SECRET = 'legacy-plain' WHERE ID = 1");

        // legacy plaintext reads back unchanged (migration path)
        $resultSet = $connection->query("SELECT * FROM E2EAttribute");
        $this->assertTrue($resultSet->hasNext());
        $reloaded = new E2EAttribute();
        $reloaded->fromResultSet($resultSet);
        $resultSet->close();
        $this->assertSame("legacy-plain", $reloaded->getSecret());

        // on the next save the value is upgraded to ciphertext
        $reloaded->save();
        $raw = $connection->query("SELECT D_SECRET FROM E2EAttribute WHERE ID = 1");
        $this->assertTrue($raw->hasNext());
        $this->assertStringStartsWith("enc:x:", $raw->getStringValue("D_SECRET"));
        $raw->close();
    }
}
