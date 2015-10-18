<?php

namespace siestaphp\tests\functional;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\generator\ValidationLogger;
use siestaphp\util\File;
use siestaphp\xmlreader\XMLReader;

/**
 * Class MigrationATest
 * @package siestaphp\tests\functional
 */
class MigrationRefTest extends SiestaTester
{

    const ASSET_PATH = "/migrationref";

    const SRC_XML = "/Original.test.xml";

    const TARGET_XML = "/Target.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall();
        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testMigrateReferences()
    {
        // 2nd generation triggers migration of database
        $this->generateEntityFile(self::ASSET_PATH, self::TARGET_XML);

        // read model
        $this->logger = new CodeceptionLogger();
        $dmc = new DataModelContainer(new ValidationLogger($this->logger));
        $xmlReader = new XMLReader(new File(__DIR__ . self::ASSET_PATH . self::TARGET_XML));
        $dmc->addEntitySourceList($xmlReader->getEntitySourceList());
        $dmc->updateModel();
        $dmc->validate();
        $artistEntiy = $dmc->getEntityByClassname("Customer");
        $this->assertNotNull($artistEntiy);

        // read meta data from database and find ArtistEntity
        $entitySourceList = $this->connection->getEntitySourceList();
        $artistDatabaseEntity = $this->getEntityByName($entitySourceList, "Customer");
        $this->assertNotNull($artistDatabaseEntity);

        // compare database and model entity
        $this->assertSame($artistEntiy->getClassName(), $artistDatabaseEntity->getClassName(), "classnames are not identical");
        $this->assertSame($artistEntiy->getTable(), $artistDatabaseEntity->getTable(), "tables are not identical");

        // compare attribtues
        $this->assertSame(sizeof($artistEntiy->getAttributeSourceList()), sizeof($artistDatabaseEntity->getAttributeSourceList()));
        foreach ($artistEntiy->getAttributeSourceList() as $attribute) {
            $databaseAttribute = $this->getAttributeByDatabaseName($artistDatabaseEntity->getAttributeSourceList(), $attribute->getDatabaseName());
            $this->assertNotNull($databaseAttribute);

            $this->assertSame($databaseAttribute->isRequired(), $attribute->isRequired());
            $this->assertSame($databaseAttribute->isPrimaryKey(), $attribute->isPrimaryKey());
            $this->assertSame($databaseAttribute->getDatabaseType(), $attribute->getDatabaseType());
        }

        // compare references
        $this->assertSame(sizeof($artistEntiy->getReferenceSourceList()), sizeof($artistDatabaseEntity->getReferenceSourceList()), "number of references not identical");
        foreach ($artistEntiy->getReferenceSourceList() as $reference) {
            $databaseReference = $this->getReferenceByConstraintName($artistDatabaseEntity->getReferenceSourceList(), $reference->getConstraintName());
            $this->assertNotNull($databaseReference, "did not find database reference for " . $reference->getConstraintName());

            $this->assertSame($reference->getForeignClass(), $databaseReference->getForeignClass(), "reference differ in foreign class");
            $this->assertSame($reference->getForeignTable(), $databaseReference->getForeignTable(), "reference differ in foreign table");
            $this->assertSame($reference->getOnDelete(), $databaseReference->getOnDelete(), "reference differ in on delete");
            $this->assertSame($reference->getOnUpdate(), $databaseReference->getOnUpdate(), "reference differ in on update");

            $this->compareReferencedColumnList($databaseReference->getReferencedColumnList(), $reference->getReferencedColumnList());
        }
    }

    /**
     * @param ReferencedColumnSource[] $databaseList
     * @param ReferencedColumnSource[] $modelList
     */
    private function compareReferencedColumnList($databaseList, $modelList)
    {
        $this->assertSame(sizeof($databaseList), sizeof($modelList));

        foreach ($modelList as $model) {
            $databaseRefColumn = $this->getReferencedColumnByDatabaseName($databaseList, $model->getDatabaseName());
            $this->assertNotNull($databaseRefColumn, "did not find referenced column for " . $model->getDatabaseName());

            $this->assertSame($model->getDatabaseType(), $databaseRefColumn->getDatabaseType(), "database types are not identical");
            $this->assertSame($model->getReferencedDatabaseName(), $databaseRefColumn->getReferencedDatabaseName(), "referenced database names are not identical");
            $this->assertSame($model->isPrimaryKey(), $databaseRefColumn->isPrimaryKey(), "primary key not identical");
            $this->assertSame($model->isRequired(), $databaseRefColumn->isRequired(), "required not identical");

        }

    }

    /**
     * @param ReferencedColumnSource[] $referencedColumnList
     * @param string $databaseName
     *
     * @return ReferencedColumnSource
     */
    private function getReferencedColumnByDatabaseName($referencedColumnList, $databaseName)
    {
        foreach ($referencedColumnList as $referencedColumn) {
            if ($referencedColumn->getDatabaseName() === $databaseName) {
                return $referencedColumn;
            }

        }
        return null;
    }

    /**
     * @param EntitySource[] $entitySourceList
     * @param $className
     *
     * @return EntitySource
     */
    private function getEntityByName(array $entitySourceList, $className)
    {
        foreach ($entitySourceList as $entity) {
            if ($entity->getClassName() === $className) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * @param AttributeSource[] $attributeList
     * @param $databaseName
     *
     * @return AttributeSource
     */
    private function getAttributeByDatabaseName(array $attributeList, $databaseName)
    {
        foreach ($attributeList as $attribute) {
            if ($attribute->getDatabaseName() === $databaseName) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @param ReferenceSource[] $referenceList
     * @param string $constraintName
     *
     * @return ReferenceSource
     */
    private function getReferenceByConstraintName(array $referenceList, $constraintName)
    {
        foreach ($referenceList as $reference) {
            if ($reference->getConstraintName() === $constraintName) {
                return $reference;
            }
        }
        return null;
    }
}