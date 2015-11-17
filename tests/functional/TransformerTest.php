<?php

namespace siestaphp\tests\functional;

use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\collector\CollectorGeneratorSource;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
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
     * @return Entity
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
        $dataModelContainer->validate();

        // get artist entity
        $entitySource = $dataModelContainer->getEntityByClassname("ArtistEntity");

        // check that artist entity was found
        $this->assertNotNull($entitySource, "ArtistEntity not found");

        return $entitySource;

    }

    /**
     * tests the entity attributes
     */
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

    /**
     * tests the attributes defined in the xml
     */
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
        $this->assertSame($definition["type"], $ats->getPHPType(), "Attribute $attributeName type is not correct : " . $ats->getPHPType() . " vs " . $definition["type"]);
        $this->assertSame($definition["dbName"], $ats->getDatabaseName(), "Attribute $attributeName dbName is not correct");
        $this->assertSame($definition["dbType"], $ats->getDatabaseType(), "Attribute $attributeName dbType is not correct");
        $this->assertSame($definition["primaryKey"], $ats->isPrimaryKey(), "Attribute $attributeName primaryKey is not correct");
        $this->assertSame($definition["required"], $ats->isRequired(), "Attribute $attributeName required is not correct");
        $this->assertSame($definition["defaultValue"], $ats->getDefaultValue(), "Attribute $attributeName defaultValue is not correct");
        $this->assertSame($definition["autoValue"], $ats->getAutoValue(), "Attribute $attributeName autoValue is not correct");
        $this->assertSame($definition["length"], $ats->getLength(), "Attribute $attributeName length is not correct");

        // derived data
        $this->assertSame($definition["methodName"], $ats->getMethodName(), "Attribute $attributeName methodName is not correct");

    }

    /**
     * tests the references defined in the xml
     */
    public function testReferenceList()
    {
        $entity = $this->loadEntitySource();
        foreach ($entity->getReferenceGeneratorSourceList() as $reference) {
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
        $this->assertSame($definition["foreignClass"], $referenceSource->getForeignClass(), "Reference $referenceName foreignClass is not correct");
        $this->assertSame($definition["required"], $referenceSource->isRequired(), "Reference $referenceName required is not correct");
        $this->assertSame($definition["onDelete"], $referenceSource->getOnDelete(), "Reference $referenceName onDelete is not correct");
        $this->assertSame($definition["onUpdate"], $referenceSource->getOnUpdate(), "Reference $referenceName onUpdate is not correct");
        $this->assertSame($definition["foreignConstructClass"], $referenceSource->getReferencedConstructClass(), "Reference $referenceName foreignConstructClass is not correct");
        $this->assertSame($definition["storedProcedureFinderName"], $referenceSource->getStoredProcedureFinderName(), "Reference $referenceName storedProcedureFinderName is not correct");
        $this->assertSame($definition["relationName"], $referenceSource->getRelationName(), "Reference $referenceName storedProcedureFinderName is not correct");
        $this->assertSame(true, $referenceSource->isReferenceCreatorNeeded(), "Reference $referenceName isReferenceCreator is not correct");
        $this->assertSame($definition["primaryKey"], $referenceSource->isPrimaryKey(), "Reference $referenceName primaryKey is not correct");

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
        $this->assertNotNull($definition, "Referenced Column " . $columnName . " not in definition list");

        // check values are right
        $this->assertSame($definition["type"], $column->getPHPType(), "Referenced Column $columnName type is not correct");
        $this->assertSame($definition["methodName"], $column->getMethodName(), "Referenced Column $columnName methodName is not correct");
        $this->assertSame($definition["databaseName"], $column->getDatabaseName(), "Referenced Column $columnName databaseName is not correct");

    }

    /**
     * tests the collectors defined in the xml
     */
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

        $this->assertSame($definition["referenceName"], $collectorSource->getReferenceName());
        $this->assertSame($definition["foreignClass"], $collectorSource->getForeignClass());
        $this->assertSame($definition["type"], $collectorSource->getType());
        $this->assertSame($definition["methodName"], $collectorSource->getMethodName());

    }

    private function testIndexList()
    {

        $entity = $this->loadEntitySource();

        $this->assertSame(sizeof($entity->getIndexSourceList()), 2, "Not 2 indexes found");

        foreach ($entity->getIndexSourceList() as $indexDatabaseSource) {
            $this->testIndex($indexDatabaseSource);
        }
    }

    /**
     * @param IndexSource $index
     */
    private function testIndex(IndexSource $index)
    {
        $indexName = $index->getName();

        $definitionList = TransformerXML::getIndexDefinition();
        $definition = \siestaphp\util\Util::getFromArray($definitionList, $indexName);
        $this->assertNotNull($definition, "Index " . $indexName . " not in definition list");

        $this->assertSame($index->isUnique(), $definition["unique"]);
        $this->assertSame($index->getType(), $definition["type"]);

        $this->assertSame(sizeof($index->getIndexPartSourceList()), 2, " not to indexParts found");
        foreach ($index->getIndexPartSourceList() as $indexPartSource) {
            $this->testIndexPart($indexName, $indexPartSource);
        }

    }

    /**
     * @param string $indexName
     * @param IndexPartSource $indexPart
     */
    private function testIndexPart($indexName, IndexPartSource $indexPart)
    {
        $indexPartName = $indexPart->getColumnName();

        $definitionList = TransformerXML::getIndexPartDefinition();
        $indexPartListDefinition = Util::getFromArray($definitionList, $indexName);
        $this->assertNotNull($indexPartListDefinition, "Definition for " . $indexName . " not in definition list");

        $definition = Util::getFromArray($indexPartListDefinition, $indexPartName);
        $this->assertNotNull($definition, "Definition for " . $indexPartName . " not in definition list");

        $this->assertSame($definition["sortOrder"], $indexPart->getSortOrder(), "Sort order of indexPart is not correct " . $indexPartName);
        $this->assertSame($definition["length"], $indexPart->getLength(), "Length of indexPart is not correct " . $indexPartName);

    }

    /**
     * tests the stored procedures defined
     */
    public function testStoredProcedureList()
    {
        $entity = $this->loadEntitySource();

        foreach ($entity->getStoredProcedureSourceList() as $sp) {
            $this->testStoredProcedure($sp);
        }
    }

    /**
     * @param StoredProcedureSource $spSource
     */
    private function testStoredProcedure(StoredProcedureSource $spSource)
    {
        $definition = TransformerXML::getSPDefinition();

        $this->assertSame($definition["name"], $spSource->getName(), "Stored procedure name is not correct");
        $this->assertSame($definition["modifies"], $spSource->modifies(), "Stored procedure modifies is not correct");
        $this->assertSame($definition["sql"], $spSource->getSql(), "Stored procedure sql is not correct");
        $this->assertSame($definition["mysql-sql"], $spSource->getSql("mysql"), "Stored procedure mysql-sql is not correct");
        $this->assertSame($definition["resultType"], $spSource->getResultType(), "Stored procedure resultType is not correct");

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

        $this->assertSame($definition["spName"], $spParameterSource->getStoredProcedureName(), "spName is not correct");
        $this->assertSame($definition["dbType"], $spParameterSource->getDatabaseType(), "dbType is not correct");
        $this->assertSame($definition["type"], $spParameterSource->getPHPType(), "type is not correct");
    }

}