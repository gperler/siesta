<?php

namespace Siesta\Model;

use Siesta\XML\XMLAttribute;
use Siesta\XML\XMLCollection;
use Siesta\XML\XMLCollectionMany;
use Siesta\XML\XMLEntity;
use Siesta\XML\XMLIndex;
use Siesta\XML\XMLIndexPart;
use Siesta\XML\XMLReference;
use Siesta\XML\XMLReferenceMapping;
use Siesta\XML\XMLStoredProcedure;
use Siesta\XML\XMLStoredProcedureParameter;

class XMLEntityReader
{

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    public function getEntity(Entity $entity, XMLEntity $xmlEntity)
    {
        $this->readEntityDataFromXML($entity, $xmlEntity);
        $this->readConstructorData($entity, $xmlEntity);
        $this->readServiceClassFromXML($entity, $xmlEntity);
        $this->readAttributeListFromXML($entity, $xmlEntity);
        $this->readReferenceListFromXML($entity, $xmlEntity);
        $this->readIndexListFromXML($entity, $xmlEntity);
        $this->readCollectionListFromXML($entity, $xmlEntity);
        $this->readCollectionManyListFromXML($entity, $xmlEntity);
        $this->readStoredProcedureListFromXML($entity, $xmlEntity);
        $this->readValueObjectFromXML($entity, $xmlEntity);
    }

    /**
     * @param XMLEntity $xmlEntity
     */
    protected function readEntityDataFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        $entity->setClassShortName($xmlEntity->getClassShortName());
        $entity->setNamespaceName($xmlEntity->getNamespaceName());
        $entity->setIsDelimit($xmlEntity->getIsDelimit());
        $entity->setIsReplication($xmlEntity->getIsReplication());
        $entity->setTargetPath($xmlEntity->getTargetPath());
        $entity->setTableName($xmlEntity->getTableName());
        $entity->setXmlEntity($xmlEntity);
        $entity->setCustomAttributeList($xmlEntity->getCustomAttributeList());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readConstructorData(Entity $entity, XMLEntity $xmlEntity)
    {
        $xmlConstructor = $xmlEntity->getXmlConstructor();
        if ($xmlConstructor === null) {
            return;
        }
        $constructor = $entity->newConstructor();
        $constructor->setClassName($xmlConstructor->getClassName());
        $constructor->setConstructCall($xmlConstructor->getConstructCall());
        $constructor->setConstructFactoryClassName($xmlConstructor->getConstructFactoryClassName());
    }

    /**
     * @param XMLEntity $xmlEntity
     */
    protected function readServiceClassFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        $xmlServiceClass = $xmlEntity->getXmlServiceClass();
        if ($xmlServiceClass === null) {
            return;
        }
        $serviceClass = $entity->newServiceClass();
        $serviceClass->setClassName($xmlServiceClass->getClassName());
        $serviceClass->setConstructCall($xmlServiceClass->getConstructCall());
        $serviceClass->setConstructFactoryClassName($xmlServiceClass->getConstructFactoryClassName());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readAttributeListFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLAttributeList() as $xmlAttribute) {
            $attribute = $entity->newAttribute();
            $this->readAttributeFromXML($attribute, $xmlAttribute);
        }

    }

    /**
     * @param Attribute $attribute
     * @param XMLAttribute $xmlAttribute
     */
    protected function readAttributeFromXML(Attribute $attribute, XMLAttribute $xmlAttribute)
    {
        $attribute->setAutoValue($xmlAttribute->getAutoValue());
        $attribute->setDbType($xmlAttribute->getDbType());
        $attribute->setDbName($xmlAttribute->getDbName());
        $attribute->setDefaultValue($xmlAttribute->getDefaultValue());
        $attribute->setIsPrimaryKey($xmlAttribute->getIsPrimaryKey());
        $attribute->setIsRequired($xmlAttribute->getIsRequired());
        $attribute->setIsTransient($xmlAttribute->getIsTransient());
        $attribute->setPhpName($xmlAttribute->getPhpName());
        $attribute->setPhpType($xmlAttribute->getPhpType());
        $attribute->setCustomAttributeList($xmlAttribute->getCustomAttributeList());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readReferenceListFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLReferenceList() as $xmlReference) {
            $reference = $entity->newReference();
            $this->readReferenceFromXML($reference, $xmlReference);
        }

    }

    /**
     * @param Reference $reference
     * @param XMLReference $xmlReference
     */
    protected function readReferenceFromXML(Reference $reference, XMLReference $xmlReference)
    {
        $reference->setName($xmlReference->getName());
        $reference->setConstraintName($xmlReference->getConstraintName());
        $reference->setForeignTable($xmlReference->getForeignTable());
        $reference->setOnUpdate($xmlReference->getOnUpdate());
        $reference->setOnDelete($xmlReference->getOnDelete());
        $reference->setNoConstraint($xmlReference->getNoConstraint());

        foreach ($xmlReference->getXmlReferenceMappingList() as $xmlReferenceMapping) {
            $referenceMapping = $reference->newReferenceMapping();
            $this->readReferenceMappingFromXML($referenceMapping, $xmlReferenceMapping);
        }
    }

    /**
     * @param ReferenceMapping $referenceMapping
     * @param XMLReferenceMapping $xmlReferenceMapping
     */
    protected function readReferenceMappingFromXML(ReferenceMapping $referenceMapping, XMLReferenceMapping $xmlReferenceMapping)
    {
        $referenceMapping->setLocalAttributeName($xmlReferenceMapping->getLocalAttribute());
        $referenceMapping->setForeignAttributeName($xmlReferenceMapping->getForeignAttribute());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readIndexListFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLIndexList() as $xmlIndex) {
            $index = $entity->newIndex();
            $this->readIndexFromXML($index, $xmlIndex);
        }
    }

    /**
     * @param Index $index
     * @param XMLIndex $xmlIndex
     */
    protected function readIndexFromXML(Index $index, XMLIndex $xmlIndex)
    {
        $index->setName($xmlIndex->getName());
        $index->setIndexType($xmlIndex->getIndexType());
        $index->setIsUnique($xmlIndex->getIsUnique());
        foreach ($xmlIndex->getIndexPartList() as $xmlIndexPart) {
            $indexPart = $index->newIndexPart();
            $this->readIndexPartFromXML($indexPart, $xmlIndexPart);
        }
    }

    /**
     * @param IndexPart $indexPart
     * @param XMLIndexPart $xmlIndexPart
     */
    protected function readIndexPartFromXML(IndexPart $indexPart, XMLIndexPart $xmlIndexPart)
    {
        $indexPart->setAttributeName($xmlIndexPart->getAttributeName());
        $indexPart->setLength($xmlIndexPart->getLength());
        $indexPart->setSortOrder($xmlIndexPart->getSortOrder());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readCollectionListFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLCollectionList() as $xmlCollection) {
            $collection = $entity->newCollection();
            $this->readCollectionFromXML($collection, $xmlCollection);
        }
    }

    /**
     * @param Collection $collection
     * @param XMLCollection $xmlCollection
     */
    protected function readCollectionFromXML(Collection $collection, XMLCollection $xmlCollection)
    {
        $collection->setName($xmlCollection->getName());
        $collection->setForeignTable($xmlCollection->getForeignTable());
        $collection->setForeignReferenceName($xmlCollection->getForeignReferenceName());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readCollectionManyListFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLCollectionManyList() as $xmlCollectionMany) {
            $collectionMany = $entity->newCollectionMany();
            $this->readCollectionManyFromXML($collectionMany, $xmlCollectionMany);
        }
    }

    /**
     * @param CollectionMany $collectionMany
     * @param XMLCollectionMany $xmlCollectionMany
     */
    protected function readCollectionManyFromXML(CollectionMany $collectionMany, XMLCollectionMany $xmlCollectionMany)
    {
        $collectionMany->setName($xmlCollectionMany->getName());
        $collectionMany->setForeignTable($xmlCollectionMany->getForeignTable());
        $collectionMany->setMappingTable($xmlCollectionMany->getMappingTable());
    }

    /**
     * @param XMLEntity $xmlEntity
     */
    protected function readStoredProcedureListFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLStoredProcedureList() as $xmlStoredProcedure) {
            $storedProcedure = $entity->newStoredProcedure();
            $this->readStoredProcedureFromXML($storedProcedure, $xmlStoredProcedure);
        }
    }

    /**
     * @param StoredProcedure $storedProcedure
     * @param XMLStoredProcedure $xmlStoredProcedure
     */
    protected function readStoredProcedureFromXML(StoredProcedure $storedProcedure, XMLStoredProcedure $xmlStoredProcedure)
    {
        $storedProcedure->setName($xmlStoredProcedure->getName());
        $storedProcedure->setResultType($xmlStoredProcedure->getResultType());
        $storedProcedure->setModifies($xmlStoredProcedure->getModifies());
        $storedProcedure->setStatement($xmlStoredProcedure->getStatement());
        foreach ($xmlStoredProcedure->getXmlParameterList() as $xmlParameter) {
            $parameter = $storedProcedure->newStoredProcedureParameter();
            $this->readStoredProcedureParamFromXML($parameter, $xmlParameter);
        }
    }

    /**
     * @param StoredProcedureParameter $storedProcedureParameter
     * @param XMLStoredProcedureParameter $xmlStoredProcedureParameter
     */
    protected function readStoredProcedureParamFromXML(StoredProcedureParameter $storedProcedureParameter, XMLStoredProcedureParameter $xmlStoredProcedureParameter)
    {
        $storedProcedureParameter->setName($xmlStoredProcedureParameter->getName());
        $storedProcedureParameter->setPhpType($xmlStoredProcedureParameter->getPhpType());
        $storedProcedureParameter->setSpName($xmlStoredProcedureParameter->getSpName());
        $storedProcedureParameter->setDbType($xmlStoredProcedureParameter->getDbType());
    }

    /**
     * @param Entity $entity
     * @param XMLEntity $xmlEntity
     */
    protected function readValueObjectFromXML(Entity $entity, XMLEntity $xmlEntity)
    {
        foreach ($xmlEntity->getXMLValueObjectList() as $xmlValueObject) {
            $valueObject = $entity->newValueObject();
            $valueObject->setClassName($xmlValueObject->getClassName());
            $valueObject->setMemberName($xmlValueObject->getMemberName());
        }
    }

}