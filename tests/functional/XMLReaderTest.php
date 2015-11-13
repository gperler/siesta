<?php

namespace siestaphp\tests\functional;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\index\IndexPartSource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\SPParameterSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\tests\functional\xmlreader\XMLReaderXML;
use siestaphp\util\File;
use siestaphp\util\Util;
use siestaphp\xmlreader\XMLReader;

/**
 * The XMLReaderTest checks if the xml element is read correctly. It checks for example attributes of entity, attribute
 * and reference.
 */
class XMLReaderTest extends \PHPUnit_Framework_TestCase
{

    const ASSET_PATH = "/xmlreader";

    /**
     * tries to extract artistEntity entity source from xml
     * @return \siestaphp\datamodel\entity\EntitySource
     */
    private function loadArtistEntity()
    {

        // load xml file & parse it
        $file = new File(__DIR__ . self::ASSET_PATH . "/XMLReader.test.xml");
        $xmlReader = new XMLReader($file);

        // read entities from file
        $entitySourceList = $xmlReader->getEntitySourceList();

        // check that 2 entities are found
        $this->assertSame(sizeof($entitySourceList), 2, "Must find exactly 2 entities");

        // find artist entity definition
        $artistEntitySource = null;
        foreach ($entitySourceList as $entitySource) {
            if ($entitySource->getClassName() === 'ArtistEntity') {
                $artistEntitySource = $entitySource;
            }
        }

        // check that artist entity was found
        $this->assertNotNull($artistEntitySource, "ArtistEntity not found");

        // done
        return $artistEntitySource;
    }

    public function testEntity()
    {
        $definition = XMLReaderXML::getEntityDefinition();
        $artistEntity = $this->loadArtistEntity();

        $this->assertSame($definition["name"], $artistEntity->getClassName(), "name is not correct");
        $this->assertSame($definition["namespace"], $artistEntity->getClassNamespace(), "namespace is not correct");
        $this->assertSame($definition["constructClass"], $artistEntity->getConstructorClass(), "constructClass is not correct");
        $this->assertSame($definition["constructNamespace"], $artistEntity->getConstructorNamespace(), "constructNamespace is not correct");
        $this->assertSame($definition["table"], $artistEntity->getTable(), "table is not correct");
        $this->assertSame($definition["delimit"], $artistEntity->isDelimit(), "delimit is not correct");
        $this->assertSame($definition["targetPath"], $artistEntity->getTargetPath(), "targetPath is not correct");
        $this->assertSame($definition["constructFactory"], $artistEntity->getConstructFactory(), "constructFactory is not correct");
        $this->assertSame($definition["constructFactoryFqn"], $artistEntity->getConstructFactoryFqn(), "constructFactoryFqn is not correct");

    }

    public function testAttributeList()
    {
        $artistEntity = $this->loadArtistEntity();

        foreach ($artistEntity->getAttributeSourceList() as $attributeSource) {
            $this->testAttribute($attributeSource);
        }
    }

    /**
     * checks that all attribute data is correct
     *
     * @param AttributeSource $ats
     */
    private function testAttribute(AttributeSource $ats)
    {

        $attributeName = $ats->getName();

        // get definition and check they exist
        $definitionList = XMLReaderXML::getAttributeDefinition();
        $definition = Util::getFromArray($definitionList, $attributeName);
        $this->assertNotNull($definition, "Attribute $attributeName not in definition list");

        // check attribute values
        $this->assertSame($definition["type"], $ats->getPHPType(), "Attribute $attributeName type is not correct");
        $this->assertSame($definition["dbName"], $ats->getDatabaseName(), "Attribute $attributeName dbName is not correct");
        $this->assertSame($definition["dbType"], $ats->getDatabaseType(), "Attribute $attributeName dbType is not correct");
        $this->assertSame($definition["primaryKey"], $ats->isPrimaryKey(), "Attribute $attributeName primaryKey is not correct");
        $this->assertSame($definition["required"], $ats->isRequired(), "Attribute $attributeName required is not correct");
        $this->assertSame($definition["defaultValue"], $ats->getDefaultValue(), "Attribute $attributeName defaultValue is not correct");
        $this->assertSame($definition["autoValue"], $ats->getAutoValue(), "Attribute $attributeName autoValue is not correct");
    }

    public function testReferenceList()
    {
        $artistEntity = $this->loadArtistEntity();

        foreach ($artistEntity->getReferenceSourceList() as $reference) {
            $this->testReference($reference);
        }
    }

    /**
     * check a reference
     *
     * @param ReferenceSource $referenceSource
     */
    private function testReference(ReferenceSource $referenceSource)
    {
        // get name
        $referenceName = $referenceSource->getName();

        // find definition and check they exist
        $definitionList = XMLReaderXML::getReferenceDefinition();
        $definition = Util::getFromArray($definitionList, $referenceName);
        $this->assertNotNull($definition, "Reference $referenceName not in definition list");

        // check reference values
        $this->assertSame($definition["foreignClass"], $referenceSource->getForeignClass(), "Reference $referenceName foreignClass is not correct");
        $this->assertSame($definition["required"], $referenceSource->isRequired(), "Reference $referenceName required is not correct");
        $this->assertSame($definition["onDelete"], $referenceSource->getOnDelete(), "Reference $referenceName onDelete is not correct");
        $this->assertSame($definition["onUpdate"], $referenceSource->getOnUpdate(), "Reference $referenceName onUpdate is not correct");
        $this->assertSame($definition["relationName"], $referenceSource->getRelationName(), "Reference $referenceName relationName is not correct");
        $this->assertSame($definition["primaryKey"], $referenceSource->isPrimaryKey(), "Reference $referenceName primaryKey is not correct");
    }

    public function testCollectorList()
    {

        $artistEntity = $this->loadArtistEntity();
        foreach ($artistEntity->getCollectorSourceList() as $collector) {
            $this->testCollector($collector);
        }
    }

    /**
     * @param CollectorSource $collectorSource
     */
    private function testCollector(CollectorSource $collectorSource)
    {
        // get name
        $name = $collectorSource->getName();

        // find definition and check they exist
        $definitionList = XMLReaderXML::getCollectorDefinition();
        $definition = Util::getFromArray($definitionList, $name);
        $this->assertNotNull($definition, "Collector $name not in definition list");

        $this->assertSame($definition["referenceName"], $collectorSource->getReferenceName(), "Collector $name referenceName not correct");
        $this->assertSame($definition["foreignClass"], $collectorSource->getForeignClass(), "Collector $name foreignClass not correct");
        $this->assertSame($definition["type"], $collectorSource->getType(), "Collector $name type not correct");

    }

    public function testIndexList()
    {
        $artistEntity = $this->loadArtistEntity();
        $indexList = $artistEntity->getIndexSourceList();

        $this->assertSame(2, sizeof($indexList), "indexes are missing");
        foreach ($indexList as $index) {
            $this->testIndex($index);
        }
    }

    /**
     * @param IndexSource $index
     */
    private function testIndex(IndexSource $index)
    {
        // get name
        $indexName = $index->getName();

        // find definition and check they exist
        $definitionList = XMLReaderXML::getIndexDefinition();
        $definition = Util::getFromArray($definitionList, $indexName);
        $this->assertNotNull($definition, "Index $indexName not in definition list");

        $this->assertSame($definition["unique"], $index->isUnique(), "Index $indexName unique not correct");
        $this->assertSame($definition["type"], $index->getType(), "Index $indexName type not correct");

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

        $definitionList = XMLReaderXML::getIndexPartDefinition();
        $indexPartListDefinition = Util::getFromArray($definitionList, $indexName);
        $this->assertNotNull($indexPartListDefinition, "Definition for $indexName not in definition list");

        $indexPartDefinition = Util::getFromArray($indexPartListDefinition, $indexPartName);
        $this->assertNotNull($indexPartDefinition, "Definition for $indexPartName not in definition list");

        $this->assertSame($indexPartDefinition["sortOrder"], $indexPart->getSortOrder(), "sortOrder for indexPart $indexPartName not correct");
        $this->assertSame($indexPartDefinition["length"], $indexPart->getLength(), "length for indexPart $indexPartName not correct");

    }

    public function testSPList()
    {
        $artistEntity = $this->loadArtistEntity();

        foreach ($artistEntity->getStoredProcedureSourceList() as $sp) {
            $this->testStoredProcedure($sp);
        }
    }

    /**
     * @param StoredProcedureSource $spSource
     */
    private function testStoredProcedure(StoredProcedureSource $spSource)
    {
        $spDefinition = XMLReaderXML::getSPDefinition();
        $this->assertSame($spDefinition["name"], $spSource->getName(), "name not correct");
        $this->assertSame($spDefinition["modifies"], $spSource->modifies(), "modifies not correct");
        $this->assertSame($spDefinition["sql"], $spSource->getSql(), "sql not correct");
        $this->assertSame($spDefinition["mysql-sql"], $spSource->getSql("mysql"), "mysql-sql not correct");
        $this->assertSame($spDefinition["resultType"], $spSource->getResultType(), "resultType not correct");

        $this->assertSame(2, sizeof($spSource->getParameterList()), "not 2 parameters found");
        foreach ($spSource->getParameterList() as $param) {
            $this->testSPParameter($param);
        }

    }

    /**
     * @param SPParameterSource $spParameterSource
     */
    private function testSPParameter(SPParameterSource $spParameterSource)
    {
        $definitionList = XMLReaderXML::getSPParameterDefinition();

        // find definition
        $paramName = $spParameterSource->getName();
        $definition = Util::getFromArray($definitionList, $paramName);
        $this->assertNotNull($definition, "no definition for parameter $paramName found");

        $this->assertSame($definition["spName"], $spParameterSource->getStoredProcedureName(), "Param $paramName spName not correct");
        $this->assertSame($definition["dbType"], $spParameterSource->getDatabaseType(), "Param $paramName dbType not correct");
        $this->assertSame($definition["type"], $spParameterSource->getPHPType(), "Param $paramName type not correct");
    }

}
