<?php

namespace SiestaTest\Functional\Model;

use Codeception\Test\Unit;
use Siesta\Model\DataModel;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\NamingStrategy\NoTransformStrategy;
use Siesta\NamingStrategy\ToUnderScoreStrategy;
use Siesta\Util\File;
use Siesta\XML\XMLReader;

class ModelUpdateTest extends Unit
{

    protected function readSchema(string $fileName)
    {
        $xmlReader = new XMLReader(new File(__DIR__ . $fileName));
        $xmlEntityList = $xmlReader->getEntityList();

        $datamodel = new DataModel();
        $datamodel->addXMLEntityList($xmlEntityList);

        $datamodel->update();

        return $datamodel;
    }

    /**
     *
     */
    public function testReferenceUpdate()
    {

        $datamodel = $this->readSchema("/schema/ModelUpdate.test.xml");

        $this->assertNotNull($datamodel);

        $referenceEntity = $datamodel->getEntityByTableName("Reference");
        $this->assertNotNull($referenceEntity);

        $reference1 = $referenceEntity->getReferenceByName("reference1");
        $this->assertNotNull($reference1);
        $this->assertSame(2, sizeof($reference1->getReferenceMappingList()));
        foreach ($reference1->getReferenceMappingList() as $referenceMapping) {
            $this->assertNotNull($referenceMapping->getForeignAttribute());
            $this->assertNotNull($referenceMapping->getLocalAttribute());
        }

        $reference2 = $referenceEntity->getReferenceByName("reference2");
        $this->assertNotNull($reference2);

        $mappingList = $reference2->getReferenceMappingList();
        $this->assertSame(1, sizeof($mappingList));

        $mapping = $mappingList[0];

        $this->assertNotNull($mapping->getLocalAttribute());
        $this->assertNotNull($mapping->getForeignAttribute());

        // well just for testing
        $this->assertSame("FK_Artist", $mapping->getLocalAttribute()->getDBName());
        $this->assertSame("id", $mapping->getForeignAttribute()->getDBName());

    }

    public function testIndexUpdate()
    {
        $datamodel = $this->readSchema("/schema/ModelUpdate.test.xml");
        $this->assertNotNull($datamodel);

        $referenceEntity = $datamodel->getEntityByTableName("Artist");

        $index1 = $referenceEntity->getIndexByName("indexName");
        $this->assertNotNull($index1);

        foreach ($index1->getIndexPartList() as $indexPart) {
            $this->assertNotNull($indexPart->getAttribute());
        }

        $index2 = $referenceEntity->getIndexByName("indexName2");
        $this->assertNotNull($index2);

        $indexPartList = $index2->getIndexPartList();
        $this->assertSame(1, sizeof($indexPartList));

        $indexPart = $indexPartList[0];

        $this->assertNotNull($indexPart);
        $this->assertSame("VARCHAR(123)", $indexPart->getAttribute()->getDbType());
    }

    public function testCollectionUpdate()
    {
        $datamodel = $this->readSchema("/schema/ModelUpdate.test.xml");
        $this->assertNotNull($datamodel);

        $entity = $datamodel->getEntityByTableName("Artist");

        $collection = $entity->getCollectionByName("collection1");
        $this->assertNotNull($collection);

        $foreignReference = $collection->getForeignReference();
        $this->assertNotNull($foreignReference);
        $this->assertSame("testValue", $foreignReference->getOnDelete());

        $foreignEntity = $collection->getForeignEntity();
        $this->assertNotNull($foreignEntity);
        $this->assertSame("ReferenceTest", $foreignEntity->getClassShortName());
    }

    public function testCollectionManyUpdate()
    {
        $datamodel = $this->readSchema("/schema/ModelUpdate.test.xml");
        $this->assertNotNull($datamodel);

        $studentEntity = $datamodel->getEntityByTableName("Student");
        $this->assertNotNull($studentEntity);

        $collectionMany = $studentEntity->getCollectionManyByName("ExamList");
        $this->assertNotNull($collectionMany);

        $foreignEntity = $collectionMany->getForeignEntity();
        $this->assertNotNull($foreignEntity);
        $this->assertSame("---Exam---", $foreignEntity->getNamespaceName());

        $foreignReference = $collectionMany->getForeignReference();
        $this->assertNotNull($foreignReference);
        $this->assertSame("ExamReference", $foreignReference->getName());

        $mappingEntity = $collectionMany->getMappingEntity();
        $this->assertNotNull($mappingEntity);
        $this->assertSame("---MappingTable---", $mappingEntity->getNamespaceName());

        $mappingReference = $collectionMany->getMappingReference();
        $this->assertNotNull($mappingReference);
        $this->assertSame("StudentReference", $mappingReference->getName());

    }

    /**
     *
     */
    public function testCamelCaseNamingStrategy()
    {
        NamingStrategyRegistry::setColumnNamingStrategy(new ToUnderScoreStrategy());
        $datamodel = $this->readSchema("/schema/Model.naming.strategy.test.xml");
        $this->assertNotNull($datamodel);

        $artist = $datamodel->getEntityByTableName("Artist");
        $this->assertNotNull($artist);

        $attribute = $artist->getAttributeByName("customerFirstName");
        $this->assertNotNull($attribute);
        $this->assertSame("customer_first_name", $attribute->getDBName());

        $attribute = $artist->getAttributeByName("customerLastName");
        $this->assertNotNull($attribute);
        $this->assertSame("customer_last_name", $attribute->getDBName());

        $attribute = $artist->getAttributeByName("cityStateName");
        $this->assertNotNull($attribute);
        $this->assertSame("city_state_name", $attribute->getDBName());

        NamingStrategyRegistry::setColumnNamingStrategy(new NoTransformStrategy());

    }

}