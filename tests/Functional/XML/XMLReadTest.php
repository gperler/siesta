<?php

namespace SiestaTest\Functional\XML;

use Codeception\Test\Unit;
use Siesta\Util\File;
use Siesta\XML\XMLReader;

class XMLReadTest extends Unit
{

    const ENTITY_COUNT = 6;

    public function testReadEntity()
    {

        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[0];

        $this->assertSame("ArtistEntity", $xmlEntity->getClassShortName());
        $this->assertSame("ARTIST", $xmlEntity->getTableName());
        $this->assertSame(true, $xmlEntity->getIsDelimit());
        $this->assertSame("SiestaTest\\Functional\\XML\\Artist", $xmlEntity->getNamespaceName());

        $this->assertSame("test/123", $xmlEntity->getTargetPath());
        $this->assertTrue($xmlEntity->getIsReplication());

        $xmlConstructor = $xmlEntity->getXmlConstructor();
        $this->assertNotNull($xmlConstructor);
        $this->assertSame("className", $xmlConstructor->getClassName());
        $this->assertSame("constructCall", $xmlConstructor->getConstructCall());
        $this->assertSame("constructFactoryClassName", $xmlConstructor->getConstructFactoryClassName());

        $xmlServiceClass = $xmlEntity->getXmlServiceClass();
        $this->assertNotNull($xmlServiceClass);
        $this->assertSame("serviceClassClassName", $xmlServiceClass->getClassName());
        $this->assertSame("serviceClassConstructFactory", $xmlServiceClass->getConstructCall());
        $this->assertSame("serviceConstructFactoryClassName", $xmlServiceClass->getConstructFactoryClassName());

        $this->assertSame("customValue", $xmlEntity->getCustomAttribute("customAttribute"));
        $mySQLValueList = $xmlEntity->getDatabaseSpecificAttributeList("mysql");
        $this->assertSame(2, sizeof($mySQLValueList));
        $this->assertSame("unicode", $mySQLValueList["collate"]);
        $this->assertSame("MEMORY", $mySQLValueList["engine"]);

        $xmlValueList = $xmlEntity->getXMLValueObjectList();
        $this->assertSame(2, sizeof($xmlValueList));
        $this->assertSame("test1", $xmlValueList[0]->getClassName());
        $this->assertSame("member1", $xmlValueList[0]->getMemberName());
        $this->assertSame("test2", $xmlValueList[1]->getClassName());
        $this->assertSame("member2", $xmlValueList[1]->getMemberName());


    }

    public function testReadAttribute()
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[1];

        $this->assertNull($xmlEntity->getXmlConstructor());
        $this->assertNull($xmlEntity->getXmlServiceClass());

        $xmlAttributeList = $xmlEntity->getXMLAttributeList();
        $this->assertSame(8, sizeof($xmlAttributeList));

        $xmlAttribute = $xmlAttributeList[0];

        $this->assertSame("autoincrement", $xmlAttribute->getAutoValue());
        $this->assertSame("ID", $xmlAttribute->getDbName());
        $this->assertSame("INT", $xmlAttribute->getDbType());
        $this->assertSame("42", $xmlAttribute->getDefaultValue());

        $this->assertSame(true, $xmlAttribute->getIsPrimaryKey());
        $this->assertSame(true, $xmlAttribute->getIsRequired());
        $this->assertSame(true, $xmlAttribute->getIsTransient());
        $this->assertSame("id", $xmlAttribute->getPhpName());
        $this->assertSame("int", $xmlAttribute->getPhpType());

    }

    public function testReadIndex()
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[2];

        $xmlIndexList = $xmlEntity->getXMLIndexList();
        $this->assertSame(2, sizeof($xmlIndexList));

        $xmlIndex = $xmlIndexList[1];
        $this->assertSame("indexName2", $xmlIndex->getName());
        $this->assertSame("btree", $xmlIndex->getIndexType());
        $this->assertSame(true, $xmlIndex->getIsUnique());

        $indexPartList = $xmlIndex->getIndexPartList();
        $this->assertSame(2, sizeof($indexPartList));

        $indexPart = $indexPartList[0];

        $this->assertSame("D_STRING", $indexPart->getAttributeName());
        $this->assertSame("ASC", $indexPart->getSortOrder());
        $this->assertSame(10, $indexPart->getLength());
    }

    public function testReadStoredProcedure()
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[3];

        $xmlStoredProcedureList = $xmlEntity->getXMLStoredProcedureList();
        $this->assertSame(1, sizeof($xmlStoredProcedureList));

        $xmlStoredProcedure = $xmlStoredProcedureList[0];

        $this->assertSame("getFirstArtistByCity", $xmlStoredProcedure->getName());
        $this->assertSame(true, $xmlStoredProcedure->getModifies());
        $this->assertSame("single", $xmlStoredProcedure->getResultType());

        $this->assertSame("SP_STATEMENT;", $xmlStoredProcedure->getStatement());

        $xmlSPParameterList = $xmlStoredProcedure->getXmlParameterList();
        $this->assertSame(2, sizeof($xmlSPParameterList));
        $xmlSPParameter = $xmlSPParameterList[1];

        $this->assertSame("test2", $xmlSPParameter->getName());
        $this->assertSame("P_CITY2", $xmlSPParameter->getSpName());
        $this->assertSame("int", $xmlSPParameter->getPhpType());
        $this->assertSame("INT", $xmlSPParameter->getDbType());

    }

    public function testReadCollection()
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[4];

        $xmlCollectionList = $xmlEntity->getXMLCollectionList();
        $this->assertSame(3, sizeof($xmlCollectionList));

        $xmlCollection = $xmlCollectionList[1];
        $this->assertSame("2", $xmlCollection->getName());
        $this->assertSame("table2", $xmlCollection->getForeignTable());
        $this->assertSame("frn_2", $xmlCollection->getForeignReferenceName());

    }

    public function testReadCollectionMany()
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[4];

        $xmlCollectionManyList = $xmlEntity->getXMLCollectionManyList();
        $this->assertSame(2, sizeof($xmlCollectionManyList));

        $xmlCollectionMany = $xmlCollectionManyList[1];
        $this->assertSame("m2m_2", $xmlCollectionMany->getName());
        $this->assertSame("m2m_t_2", $xmlCollectionMany->getForeignTable());
        $this->assertSame("m2m_mt_2", $xmlCollectionMany->getMappingTable());

    }

    public function testReadReference()
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/XMLRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[5];

        $referenceList = $xmlEntity->getXMLReferenceList();
        $this->assertSame(2, sizeof($referenceList));

        $reference = $referenceList[0];
        $this->assertSame("reference1", $reference->getName());
        $this->assertSame("xyz", $reference->getForeignTable());
        $this->assertSame("onDelete", $reference->getOnDelete());
        $this->assertSame("onUpdate", $reference->getOnUpdate());

        $referenceMappingList = $reference->getXmlReferenceMappingList();
        $this->assertSame(2, sizeof($referenceMappingList));

        $referenceMapping = $referenceMappingList[1];
        $this->assertSame("c", $referenceMapping->getLocalAttribute());
        $this->assertSame("d", $referenceMapping->getForeignAttribute());

    }

}