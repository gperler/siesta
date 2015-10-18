<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\util\File;
use siestaphp\util\Util;

/**
 * Class AttributeTest
 */
class ReverseTest extends SiestaTester
{

    const ASSET_PATH = "/reverse";

    const SRC_XML = "/Reverse.test.xml";

    const ATTRIBUTE_DATA = "/artist.json";

    const REFERENCE_DATA = "/reference.json";

    protected function setUp()
    {
        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array());

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    /**
     * @return int
     */
    public function testReverseAttributeList()
    {
        $attributeJSONList = $this->loadJSON(__DIR__ . self::ASSET_PATH . self::ATTRIBUTE_DATA);

        $entitySourcelist = $this->connection->getEntitySourceList();

        $entity = $this->findEntityByTableName($entitySourcelist, "ARTIST");
        $this->assertNotNull($entity);

        foreach ($entity->getAttributeSourceList() as $attribute) {
            $dbName = $attribute->getDatabaseName();
            $jsonData = Util::getFromArray($attributeJSONList, $dbName);
            $this->assertNotNull($jsonData, "Found no definition for " . $dbName);
            $this->assertSame($attribute->getDatabaseType(), $jsonData["type"], "Database type from $dbName not correct");
            $this->assertSame($attribute->getPHPType(), $jsonData["phpType"], "PHP type from $dbName not correct");
            $this->assertSame($attribute->isRequired(), $jsonData["required"], "required type from $dbName not correct");
        }
    }

    public function testReverseReferenceList()
    {

        $entitySourcelist = $this->connection->getEntitySourceList();

        $addressEntity = $this->findEntityByTableName($entitySourcelist, "Address");
        $this->assertNotNull($addressEntity, "Address entity not found");

        // check reference
        $referenceJSON = $this->loadJSON(__DIR__ . self::ASSET_PATH . self::REFERENCE_DATA);

        $referenceSourceList = $addressEntity->getReferenceSourceList();
        $this->assertSame(sizeof($referenceSourceList), 1, "not exactly one reference found");

        $reference = $referenceSourceList[0];
        $this->assertSame($reference->getName(), $referenceJSON["name"]);
        $this->assertSame($reference->getConstraintName(), $referenceJSON["constraintName"]);
        $this->assertSame($reference->getForeignClass(), $referenceJSON["foreignClass"]);
        $this->assertSame($reference->getForeignTable(), $referenceJSON["foreignClass"]);
        $this->assertSame($reference->getOnUpdate(), $referenceJSON["onUpdate"]);
        $this->assertSame($reference->getOnDelete(), $referenceJSON["onDelete"]);

        // check reference list
        $referencedColumnList = $reference->getReferencedColumnList();
        $this->assertSame(sizeof($referencedColumnList), 2, "not exatly 2 referenced columns found");

        foreach ($referencedColumnList as $referencedColumn) {
            $name = $referencedColumn->getName();
            $columnJSON = Util::getFromArray($referenceJSON["referenceSource"], $name);
            $this->assertNotNull($columnJSON, "Did not find JSON Data for reference " . $name);
            $this->assertSame($referencedColumn->getDatabaseType(), $columnJSON["dbType"], "Database type not correct");
            $this->assertSame($referencedColumn->getReferencedDatabaseName(), $columnJSON["foreignColumn"], "Foreign column not correct");
        }

        // check mapping list
        $mappingList = $reference->getMappingSourceList();
        $this->assertSame(sizeof($mappingList), 2, "Expected exactly 2 mappings");
        foreach ($mappingList as $mapping) {
            $name = $mapping->getName();
            $mappingJSON = Util::getFromArray($referenceJSON["mappingSource"], $name);
            $this->assertSame($mapping->getDatabaseName(), $mappingJSON["databaseName"]);
            $this->assertSame($mapping->getForeignName(), $mappingJSON["foreignColumn"]);
            $this->assertSame($mapping->getDatabaseType(), $mappingJSON["databaseType"]);
        }

    }

    /**
     * @param EntitySource[] $entitySourcelist
     * @param string $tableName
     *
     * @return EntitySource
     */
    private function findEntityByTableName($entitySourcelist, $tableName)
    {
        foreach ($entitySourcelist as $entitySource) {
            if ($entitySource->getTable() === $tableName) {
                return $entitySource;
            }
        }
        return null;
    }

}