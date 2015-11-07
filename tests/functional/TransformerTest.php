<?php

namespace siestaphp\tests\functional;

use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\collector\CollectorGeneratorSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\index\IndexGeneratorSource;
use siestaphp\datamodel\index\IndexPartGeneratorSource;
use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\datamodel\reference\ReferenceGeneratorSource;
use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\generator\ValidationLogger;
use siestaphp\tests\functional\transformer\TransformerXML;
use siestaphp\util\File;
use siestaphp\util\Util;
use siestaphp\xmlreader\XMLReader;

/**
 * Class TransformerTest
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{

    const ASSET_PATH = "/transformer";

    /**
     * @return \siestaphp\datamodel\entity\Entity
     */
    private function loadEntitySource()
    {
        // read file
        $file = new File(__DIR__ . self::ASSET_PATH . "/Transformer.test.xml");
        $xmlReader = new XMLReader($file);

        // get entities
        $entitySourceList = $xmlReader->getEntitySourceList();

        // create datamodel
        $dataModelContainer = new DataModelContainer(new ValidationLogger(new CodeceptionLogger()));
        $dataModelContainer->addEntitySourceList($entitySourceList);
        $dataModelContainer->updateModel();

        // get artist entity
        $entitySource = $dataModelContainer->getEntityByClassname("ArtistEntity");

        // check that artist entity was found
        $this->assertNotNull($entitySource, "ArtistEntity not found");

        return $entitySource;

    }

    public function testEntity()
    {
        $entity = $this->loadEntitySource();
        $definition = TransformerXML::getEntityTransformerDefinition();

        $this->assertSame($definition["name"], $entity->getClassName(), "name is not correct");
        $this->assertSame($definition["namespace"], $entity->getClassNamespace(), "namespace is not correct");
        $this->assertSame($definition["constructClass"], $entity->getConstructorClass(), "constructClass is not correct");
        $this->assertSame($definition["constructNamespace"], $entity->getConstructorNamespace(), "constructNamespace is not correct");
        $this->assertSame($definition["table"], $entity->getTable(), "table is not correct");
        $this->assertSame($definition["delimit"], $entity->isDelimit(), "delimit is not correct");
        $this->assertSame($definition["targetPath"], $entity->getTargetPath(), "targetPath is not correct");

        $this->assertSame($definition["constructFactory"], $entity->getConstructFactory(), "constructFactory is not correct");
        $this->assertSame($definition["constructFactoryFqn"], $entity->getConstructFactoryFqn(), "constructFactoryFqn is not correct");

        $this->assertSame($definition["dateTimeInUse"], $entity->isDateTimeUsed(), "dateTimeInUse is not correct");
        $this->assertSame($definition["hasReferences"], $entity->hasReferences(), "hasReferences is not correct");
        $this->assertSame($definition["hasAttributes"], $entity->hasAttributes(), "hasAttributes is not correct");
    }

    public function testAttributeList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getAttributeGeneratorSourceList() as $attributeSource) {
            $this->testAttribute($attributeSource);
        }
    }

    /**
     * tests an attribute
     *
     * @param AttributeGeneratorSource $ats
     */
    private function testAttribute(AttributeGeneratorSource $ats)
    {
        // get name
        $attributeName = $ats->getName();

        // get definition
        $definitionList = TransformerXML::getAttributeTransformerDefinition();
        $definition = Util::getFromArray($definitionList, $attributeName);
        $this->assertNotNull($definition, "Attribute " . $attributeName . " not in definition list");

        // check attribute values
        $this->assertSame($ats->getPHPType(), $definition["type"], "Attribute $attributeName type is not correct : " . $ats->getPHPType() . " vs " . $definition["type"]);
        $this->assertSame($ats->getDatabaseName(), $definition["dbName"], "Attribute $attributeName dbName is not correct");
        $this->assertSame($ats->getDatabaseType(), $definition["dbType"], "Attribute $attributeName dbType is not correct");
        $this->assertSame($ats->isPrimaryKey(), $definition["primaryKey"], "Attribute $attributeName primaryKey is not correct");
        $this->assertSame($ats->isRequired(), $definition["required"], "Attribute $attributeName required is not correct");
        $this->assertSame($ats->getDefaultValue(), $definition["defaultValue"], "Attribute $attributeName defaultValue is not correct");
        $this->assertSame($ats->getAutoValue(), $definition["autoValue"], "Attribute $attributeName autoValue is not correct");
        $this->assertSame($ats->getLength(), $definition["length"], "Attribute $attributeName length is not correct");

        // derived data
        $this->assertSame($ats->getMethodName(), $definition["methodName"], "Attribute $attributeName methodName is not correct");

    }

    public function testReferenceList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getReferenceSourceList() as $reference) {
            $this->testReference($reference);
        }
    }

    /**
     * checks a reference
     *
     * @param ReferenceGeneratorSource $referenceSource
     */
    private function testReference(ReferenceGeneratorSource $referenceSource)
    {

        // get name
        $referenceName = $referenceSource->getName();

        // get definition
        $definitionList = TransformerXML::getReferenceTransformerDefinition();
        $definition = Util::getFromArray($definitionList, $referenceName);

        // check that reference exists
        $this->assertNotNull($definition, "Reference " . $referenceName . " not in definition list");

        // check reference values
        $this->assertSame($referenceSource->getForeignClass(), $definition["foreignClass"], "Reference $referenceName foreignClass is not correct");
        $this->assertSame($referenceSource->isRequired(), $definition["required"], "Reference $referenceName required is not correct");
        $this->assertSame($referenceSource->getOnDelete(), $definition["onDelete"], "Reference $referenceName onDelete is not correct");
        $this->assertSame($referenceSource->getOnUpdate(), $definition["onUpdate"], "Reference $referenceName onUpdate is not correct");
        $this->assertSame($referenceSource->getReferencedConstructClass(), $definition["foreignConstructClass"], "Reference $referenceName foreignConstructClass is not correct");
        $this->assertSame($referenceSource->getStoredProcedureFinderName(), $definition["storedProcedureFinderName"], "Reference $referenceName storedProcedureFinderName is not correct");
        $this->assertSame($referenceSource->getRelationName(), $definition["relationName"], "Reference $referenceName storedProcedureFinderName is not correct");
        $this->assertSame($referenceSource->isReferenceCreatorNeeded(), true, "Reference $referenceName isReferenceCreator is not correct");
        $this->assertSame($referenceSource->isPrimaryKey(), $definition["primaryKey"], "Reference $referenceName primaryKey is not correct");

        // iterate referenced columns
        foreach ($referenceSource->getReferencedColumnList() as $column) {
            $this->testReferencedColunm($column, $definition["columnList"]);
        }
    }

    /**
     * tests a referenced column
     *
     * @param ReferencedColumnSource $column
     * @param array $data
     */
    private function testReferencedColunm(ReferencedColumnSource $column, array $data)
    {
        // get name
        $columnName = $column->getName();

        // get data
        $definition = Util::getFromArray($data, $columnName);

        // check that data exists
        $this->assertNotNull($definition, "Referenced Column " . $columnName . " not in definition list");

        // check values are right
        $this->assertSame($column->getPHPType(), $definition["type"], "Referenced Column $columnName type is not correct");
        $this->assertSame($column->getMethodName(), $definition["methodName"], "Referenced Column $columnName methodName is not correct");
        $this->assertSame($column->getDatabaseName(), $definition["databaseName"], "Referenced Column $columnName databaseName is not correct");

    }

    public function testCollectorList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getCollectorSourceList() as $collector) {
            $this->testCollector($collector);
        }
    }

    /**
     * @param CollectorGeneratorSource $collectorSource
     */
    private function testCollector(CollectorGeneratorSource $collectorSource)
    {
        // get name
        $name = $collectorSource->getName();

        // find definition
        $definitionList = TransformerXML::getCollectorTransformerDefinition();
        $definition = Util::getFromArray($definitionList, $name);
        $this->assertNotNull($definition, "Collector " . $name . " not in definition list");

        $this->assertSame($collectorSource->getReferenceName(), $definition["referenceName"]);
        $this->assertSame($collectorSource->getForeignClass(), $definition["foreignClass"]);
        $this->assertSame($collectorSource->getType(), $definition["type"]);
        $this->assertSame($collectorSource->getMethodName(), $definition["methodName"]);

    }

    private function testIndexList()
    {

        $entity = $this->loadEntitySource();

        $this->assertSame(sizeof($entity->getIndexSourceList()), 2, "Not 2 indexes found");
        $this->assertSame(sizeof($entity->getIndexGeneratorSourceList()), 2, "Not 2 indexes found");

        foreach ($entity->getIndexGeneratorSourceList() as $indexDatabaseSource) {
            $this->testIndex($indexDatabaseSource);
        }
    }

    /**
     * @param IndexGeneratorSource $index
     */
    private function testIndex(IndexGeneratorSource $index)
    {
        $indexName = $index->getName();

        $definitionList = TransformerXML::getIndexDefinition();
        $definition = \siestaphp\util\Util::getFromArray($definitionList, $indexName);
        $this->assertNotNull($definition, "Index " . $indexName . " not in definition list");

        $this->assertSame($index->isUnique(), $definition["unique"]);
        $this->assertSame($index->getType(), $definition["type"]);

        $this->assertSame(sizeof($index->getIndexPartGeneratorSourceList()), 2, " not to indexParts found");
        foreach ($index->getIndexPartGeneratorSourceList() as $indexPartSource) {
            $this->testIndexPart($indexName, $indexPartSource);
        }

    }

    /**
     * @param string $indexName
     * @param IndexPartGeneratorSource $indexPart
     */
    private function testIndexPart($indexName, IndexPartGeneratorSource $indexPart)
    {
        $indexPartName = $indexPart->getColumnName();

        $definitionList = TransformerXML::getIndexPartDefinition();
        $indexPartListDefinition = Util::getFromArray($definitionList, $indexName);
        $this->assertNotNull($indexPartListDefinition, "Definition for " . $indexName . " not in definition list");

        $indexPartDefinition = Util::getFromArray($indexPartListDefinition, $indexPartName);
        $this->assertNotNull($indexPartDefinition, "Definition for " . $indexPartName . " not in definition list");

        $this->assertSame($indexPart->getSortOrder(), $indexPartDefinition["sortOrder"]);
        $this->assertSame($indexPart->getLength(), $indexPartDefinition["length"]);

        $indexPart->getIndexColumnList();

    }

    public function testStoredProcedureList()
    {
        $entity = $this->loadEntitySource();

        foreach ($entity as $sp) {
            $this->testStoredProcedure($sp);
        }
    }

    /**
     * @param StoredProcedureSource $spSource
     */
    private function testStoredProcedure(StoredProcedureSource $spSource)
    {
        $spDefinition = TransformerXML::getSPDefinition();

        $this->assertSame($spSource->getName(), $spDefinition["name"]);
        $this->assertSame($spSource->modifies(), $spDefinition["modifies"]);
        $this->assertSame($spSource->getSql(), $spDefinition["sql"]);
        $this->assertSame($spSource->getSql("mysql"), $spDefinition["mysql-sql"]);
        $this->assertSame($spSource->getResultType(), $spDefinition["resultType"]);

        $this->assertSame(sizeof($spSource->getParameterList()), 2, "not 2 parameters found");
        foreach ($spSource->getParameterList() as $param) {
            $this->testSPParameter($param);
        }

    }

    /**
     * @param SPParameterSource $spParameterSource
     */
    private function testSPParameter(SPParameterSource $spParameterSource)
    {
        $definitionList = TransformerXML::getSPParameterDefinition();

        // find definition
        $definition = Util::getFromArray($definitionList, $spParameterSource->getName());
        $this->assertNotNull($definition, "no definition for parameter " . $spParameterSource->getName() . " found");

        $this->assertSame($spParameterSource->getStoredProcedureName(), $definition["spName"]);
        $this->assertSame($spParameterSource->getDatabaseType(), $definition["dbType"]);
        $this->assertSame($spParameterSource->getPHPType(), $definition["type"]);
    }

}