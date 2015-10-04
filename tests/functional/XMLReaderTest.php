<?php

use \xmlreadertest\LabelArtistXML;

require_once 'xmlreader.test/LabelArtistXML.php';

/**
 * The XMLReaderTest checks if the xml element is read correctly. It checks for example attributes of entity, attribute
 * and reference.
 */
class XMLReaderTest extends \PHPUnit_Framework_TestCase
{

    const ASSET_PATH = "/xmlreader.test";

    /**
     * tries to extract artistEntity entity source from xml
     * @return \siestaphp\datamodel\entity\EntitySource
     */
    private function loadArtistEntity()
    {

        // load xml file & parse it
        $file = new \siestaphp\util\File(__DIR__ . self::ASSET_PATH . "/LabelArtist.test.xml");
        $xmlReader = new \siestaphp\xmlreader\XMLReader($file);

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
        $definition = LabelArtistXML::getEntityDefinition();
        $artistEntity = $this->loadArtistEntity();

        $this->assertSame($artistEntity->getClassName(), $definition["name"], "name is not correct");
        $this->assertSame($artistEntity->getClassNamespace(), $definition["namespace"], "namespace is not correct");
        $this->assertSame($artistEntity->getConstructorClass(), $definition["constructClass"], "constructClass is not correct");
        $this->assertSame($artistEntity->getConstructorNamespace(), $definition["constructNamespace"], "constructNamespace is not correct");
        $this->assertSame($artistEntity->getTable(), $definition["table"], "table is not correct");
        $this->assertSame($artistEntity->isDelimit(), $definition["delimit"], "delimit is not correct");
        $this->assertSame($artistEntity->getTargetPath(), $definition["targetPath"], "targetPath is not correct");
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
     * @param \siestaphp\datamodel\attribute\AttributeSource $ats
     */
    private function testAttribute(\siestaphp\datamodel\attribute\AttributeSource $ats)
    {

        $attributeName = $ats->getName();

        // get definition and check they exist
        $definitionList = LabelArtistXML::getAttributeDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $attributeName);
        $this->assertNotNull($definition, "Attribute " . $attributeName . " not in definition list");

        // check attribute values
        $this->assertSame($ats->getPHPType(), $definition["type"], "Attribute $attributeName type is not correct");
        $this->assertSame($ats->getDatabaseName(), $definition["dbName"], "Attribute $attributeName dbName is not correct");
        $this->assertSame($ats->getDatabaseType(), $definition["dbType"], "Attribute $attributeName dbType is not correct");
        $this->assertSame($ats->isPrimaryKey(), $definition["primaryKey"], "Attribute $attributeName primaryKey is not correct");
        $this->assertSame($ats->isRequired(), $definition["required"], "Attribute $attributeName required is not correct");
        $this->assertSame($ats->getDefaultValue(), $definition["defaultValue"], "Attribute $attributeName defaultValue is not correct");
        $this->assertSame($ats->getAutoValue(), $definition["autoValue"], "Attribute $attributeName autoValue is not correct");
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
     * @param \siestaphp\datamodel\reference\ReferenceSource $referenceSource
     */
    private function testReference(\siestaphp\datamodel\reference\ReferenceSource $referenceSource)
    {
        // get name
        $referenceName = $referenceSource->getName();

        // find definition and check they exist
        $definitionList = LabelArtistXML::getReferenceDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $referenceName);
        $this->assertNotNull($definition, "Reference " . $referenceName . " not in definition list");

        // check reference values
        $this->assertSame($referenceSource->getForeignClass(), $definition["foreignClass"], "Reference $referenceName foreignClass is not correct");
        $this->assertSame($referenceSource->isRequired(), $definition["required"], "Reference $referenceName required is not correct");
        $this->assertSame($referenceSource->getOnDelete(), $definition["onDelete"], "Reference $referenceName onDelete is not correct");
        $this->assertSame($referenceSource->getOnUpdate(), $definition["onUpdate"], "Reference $referenceName onUpdate is not correct");
        $this->assertSame($referenceSource->getRelationName(), $definition["relationName"], "Reference $referenceName relationName is not correct");
    }

    public function testCollectorList()
    {

        $artistEntity = $this->loadArtistEntity();

        foreach ($artistEntity->getCollectorSourceList() as $collector) {
            $this->testCollector($collector);
        }
    }

    /**
     * @param \siestaphp\datamodel\collector\CollectorSource $collectorSource
     */
    private function testCollector(\siestaphp\datamodel\collector\CollectorSource $collectorSource)
    {
        // get name
        $name = $collectorSource->getName();

        // find definition and check they exist
        $definitionList = LabelArtistXML::getCollectorDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $name);
        $this->assertNotNull($definition, "Collector " . $name . " not in definition list");

        $this->assertSame($collectorSource->getReferenceName(), $definition["referenceName"]);
        $this->assertSame($collectorSource->getForeignClass(), $definition["foreignClass"]);
        $this->assertSame($collectorSource->getType(), $definition["type"]);

    }

    public function testIndexList()
    {
        $artistEntity = $this->loadArtistEntity();
        $indexList = $artistEntity->getIndexSourceList();

        $this->assertSame(sizeof($indexList), 2, "indexes are missing");
        foreach ($indexList as $index) {
            $this->testIndex($index);
        }
    }

    /**
     * @param \siestaphp\datamodel\index\IndexSource $index
     */
    private function testIndex(\siestaphp\datamodel\index\IndexSource $index)
    {
        // get name
        $indexName = $index->getName();

        // find definition and check they exist
        $definitionList = LabelArtistXML::getIndexDefinition();
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $indexName);
        $this->assertNotNull($definition, "Index " . $indexName . " not in definition list");

        $this->assertSame($index->isUnique(), $definition["unique"]);
        $this->assertSame($index->getType(), $definition["type"]);

        foreach ($index->getIndexPartSourceList() as $indexPartSource) {
            $this->testIndexPart($indexName, $indexPartSource);
        }

    }

    /**
     * @param string $indexName
     * @param \siestaphp\datamodel\index\IndexPartSource $indexPart
     */
    private function testIndexPart($indexName, \siestaphp\datamodel\index\IndexPartSource $indexPart)
    {
        $indexPartName = $indexPart->getName();

        $definitionList = LabelArtistXML::getIndexPartDefinition();
        $indexPartListDefinition = \siestaphp\util\Util::getFromIndex($definitionList, $indexName);
        $this->assertNotNull($indexPartListDefinition, "Definition for " . $indexName . " not in definition list");

        $indexPartDefinition = \siestaphp\util\Util::getFromIndex($indexPartListDefinition, $indexPartName);
        $this->assertNotNull($indexPartDefinition, "Definition for " . $indexPartName . " not in definition list");

        $this->assertSame($indexPart->getSortOrder(), $indexPartDefinition["sortOrder"]);
        $this->assertSame($indexPart->getLength(), $indexPartDefinition["length"]);

    }

    public function testSPList()
    {
        $artistEntity = $this->loadArtistEntity();

        foreach ($artistEntity->getStoredProcedureSourceList() as $sp) {
            $this->testStoredProcedure($sp);
        }
    }

    /**
     * @param \siestaphp\datamodel\storedprocedure\StoredProcedureSource $spSource
     */
    private function testStoredProcedure(\siestaphp\datamodel\storedprocedure\StoredProcedureSource $spSource)
    {
        $spDefinition = LabelArtistXML::getSPDefinition();
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
     * @param \siestaphp\datamodel\storedprocedure\SPParameterSource $spParameterSource
     */
    private function testSPParameter(\siestaphp\datamodel\storedprocedure\SPParameterSource $spParameterSource)
    {
        $definitionList = LabelArtistXML::getSPParameterDefinition();

        // find definition
        $definition = \siestaphp\util\Util::getFromIndex($definitionList, $spParameterSource->getName());
        $this->assertNotNull($definition, "no definition for parameter " . $spParameterSource->getName() . " found");

        $this->assertSame($spParameterSource->getStoredProcedureName(), $definition["spName"]);
        $this->assertSame($spParameterSource->getDatabaseType(), $definition["dbType"]);
        $this->assertSame($spParameterSource->getPHPType(), $definition["type"]);
    }

}
