<?php

namespace SiestaTest\Functional\Model;

use Codeception\Test\Unit;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\XMLEntityReader;
use Siesta\Util\File;
use Siesta\XML\XMLReader;

class ModelReadTest extends Unit
{

    const ENTITY_COUNT = 6;

    /**
     * @param int $index
     *
     * @return Entity
     */
    protected function getEntity(int $index)
    {
        $xmlReader = new XMLReader(new File(__DIR__ . "/schema/ModelRead.test.xml"));
        $xmlEntityList = $xmlReader->getEntityList();
        $this->assertSame(self::ENTITY_COUNT, sizeof($xmlEntityList));

        $xmlEntity = $xmlEntityList[$index];

        $entity = new Entity(new DataModel());

        $xmlEntityReader = new XMLEntityReader();
        $xmlEntityReader->getEntity($entity, $xmlEntity);

        return $entity;

    }

    public function testReadEntity()
    {
        $entity = $this->getEntity(0);

        $this->assertSame("ArtistEntity", $entity->getClassShortName());
        $this->assertSame("ARTIST", $entity->getTableName());
        $this->assertSame(true, $entity->getIsDelimit());
        $this->assertSame("SiestaTest\\Functional\\XML\\Artist", $entity->getNamespaceName());
        $this->assertTrue($entity->getIsReplication());
        $this->assertSame("ARTIST" . Entity::REPLICATION_TABLE_SUFFIX, $entity->getReplicationTableName());

        $relativePath = str_replace("\\", DIRECTORY_SEPARATOR, $entity->getNamespaceName());
        $this->assertSame($relativePath, $entity->getTargetPath());

        $this->assertSame("customValue", $entity->getCustomAttribute("customAttribute"));

        $constructor = $entity->getConstructor();
        $this->assertNotNull($constructor);

        $constructor = $entity->getConstructor();
        $this->assertNotNull($constructor);
        $this->assertSame("className", $constructor->getClassName());
        $this->assertSame("constructCall", $constructor->getConstructCall());
        $this->assertSame("constructFactoryClassName", $constructor->getConstructFactoryClassName());

        $serviceClass = $entity->getServiceClass();
        $this->assertNotNull($serviceClass);
        $this->assertSame("SiestaTest\\Functional\\XML\\Artist\\ServiceClass", $serviceClass->getClassName());
        $this->assertSame("ServiceClass", $serviceClass->getClassShortName());
        $this->assertSame("serviceClassConstructFactory", $serviceClass->getConstructCall());
        $this->assertSame("serviceConstructFactoryClassName", $serviceClass->getConstructFactoryClassName());

        $mySQLValueList = $entity->getDatabaseSpecificAttributeList("mysql");
        $this->assertSame(2, sizeof($mySQLValueList));
        $this->assertSame("unicode", $mySQLValueList["collate"]);
        $this->assertSame("MEMORY", $mySQLValueList["engine"]);

        $valueObjectList = $entity->getValueObjectList();
        $this->assertSame(2, sizeof($valueObjectList));
        $this->assertSame("test1", $valueObjectList[0]->getClassName());
        $this->assertSame("member1", $valueObjectList[0]->getMemberName());

        $this->assertSame("test2", $valueObjectList[1]->getClassName());
        $this->assertSame("member2", $valueObjectList[1]->getMemberName());

    }

    public function testReadAttribute()
    {

        $entity = $this->getEntity(1);

        $this->assertSame("gen/test", $entity->getTargetPath());

        $this->assertNull($entity->getConstructor());
        $this->assertNull($entity->getServiceClass());

        $attributeList = $entity->getAttributeList();
        $this->assertSame(8, sizeof($attributeList));

        $attribute = $attributeList[0];

        $this->assertSame("autoincrement", $attribute->getAutoValue());
        $this->assertSame("ID", $attribute->getDBName());
        $this->assertSame("INT", $attribute->getDbType());
        $this->assertSame("42", $attribute->getDefaultValue());

        $this->assertSame(true, $attribute->getIsPrimaryKey());
        $this->assertSame(true, $attribute->getIsRequired());
        $this->assertTrue(true, $attribute->getIsTransient());
        $this->assertSame("id", $attribute->getPhpName());
        $this->assertSame("int", $attribute->getPhpType());
    }

    public function testReadIndex()
    {
        $entity = $this->getEntity(2);


        $indexList = $entity->getIndexList();
        $this->assertSame(2, sizeof($indexList));

        $index = $indexList[1];
        $this->assertSame("indexName2", $index->getName());
        $this->assertSame("btree", $index->getIndexType());
        $this->assertSame(true, $index->getIsUnique());

        $indexPartList = $index->getIndexPartList();
        $this->assertSame(2, sizeof($indexPartList));

        $indexPart = $indexPartList[0];

        $this->assertSame("string", $indexPart->getAttributeName());
        $this->assertSame("ASC", $indexPart->getSortOrder());
        $this->assertSame(10, $indexPart->getLength());
    }

    public function testReadStoredProcedure()
    {
        $entity = $this->getEntity(3);


        $storedProcedureList = $entity->getStoredProcedureList();
        $this->assertSame(1, sizeof($storedProcedureList));

        $storedProcedure = $storedProcedureList[0];

        $this->assertSame("getFirstArtistByCity", $storedProcedure->getName());
        $this->assertSame(true, $storedProcedure->getModifies());
        $this->assertSame("single", $storedProcedure->getResultType());
        $this->assertSame("SP_STATEMENT;", $storedProcedure->getStatement());

        $parameterList = $storedProcedure->getParameterList();
        $this->assertSame(2, sizeof($parameterList));
        $parameter = $parameterList[1];

        $this->assertSame("test2", $parameter->getName());
        $this->assertSame("P_CITY2", $parameter->getSpName());
        $this->assertSame("int", $parameter->getPhpType());
        $this->assertSame("INT", $parameter->getDbType());

    }

    public function testReadCollection()
    {
        $entity = $this->getEntity(4);


        $collectionList = $entity->getCollectionList();
        $this->assertSame(3, sizeof($collectionList));

        $collection = $collectionList[1];
        $this->assertSame("2", $collection->getName());
        $this->assertSame("table2", $collection->getForeignTable());
        $this->assertSame("frn_2", $collection->getForeignReferenceName());

    }

    public function testReadCollectionMany()
    {
        $entity = $this->getEntity(4);


        $collectionManyList = $entity->getCollectionManyList();
        $this->assertSame(2, sizeof($collectionManyList));

        $collectionMany = $collectionManyList[1];
        $this->assertSame("m2m_2", $collectionMany->getName());
        $this->assertSame("m2m_t_2", $collectionMany->getForeignTable());
        $this->assertSame("m2m_mt_2", $collectionMany->getMappingTable());

    }

    public function testReadReference()
    {
        $entity = $this->getEntity(5);


        $referenceList = $entity->getReferenceList();
        $this->assertSame(2, sizeof($referenceList));

        $reference = $referenceList[0];
        $this->assertSame("reference1", $reference->getName());
        $this->assertSame("xyz", $reference->getForeignTable());
        $this->assertSame("onDelete", $reference->getOnDelete());
        $this->assertSame("onUpdate", $reference->getOnUpdate());

        $referenceMappingList = $reference->getReferenceMappingList();
        $this->assertSame(2, sizeof($referenceMappingList));

        $referenceMapping = $referenceMappingList[1];
        $this->assertSame("c", $referenceMapping->getLocalAttributeName());
        $this->assertSame("d", $referenceMapping->getForeignAttributeName());

    }

}