<?php
declare(strict_types=1);

namespace Siesta\Validator;

use ReflectionClass;
use ReflectionException;
use Siesta\Config\GenericGeneratorConfig;
use Siesta\Contract\AttributeValidator;
use Siesta\Contract\CollectionManyValidator;
use Siesta\Contract\CollectionValidator;
use Siesta\Contract\DataModelValidator;
use Siesta\Contract\DynamicCollectionValidator;
use Siesta\Contract\EntityValidator;
use Siesta\Contract\IndexValidator;
use Siesta\Contract\ReferenceValidator;
use Siesta\Contract\StoredProcedureValidator;
use Siesta\Contract\ValueObjectValidator;
use Siesta\Model\Attribute;
use Siesta\Model\Collection;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\DynamicCollection;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\Reference;
use Siesta\Model\StoredProcedure;
use Siesta\Model\ValidationLogger;
use Siesta\Model\ValueObject;

/**
 * @author Gregor Müller
 */
class Validator
{

    const DATA_MODEL_VALIDATOR_INTERFACE = 'Siesta\Contract\DataModelValidator';

    const ENTITY_VALIDATOR_INTERFACE = 'Siesta\Contract\EntityValidator';

    const ATTRIBUTE_VALIDATOR_INTERFACE = 'Siesta\Contract\AttributeValidator';

    const REFERENCE_VALIDATOR_INTERFACE = 'Siesta\Contract\ReferenceValidator';

    const INDEX_VALIDATOR_INTERFACE = 'Siesta\Contract\IndexValidator';

    const COLLECTION_VALIDATOR_INTERFACE = 'Siesta\Contract\CollectionValidator';

    const COLLECTION_MANY_VALIDATOR_INTERFACE = 'Siesta\Contract\CollectionManyValidator';

    const DYNAMIC_COLLECTION_VALIDATOR = 'Siesta\Contract\DynamicCollectionValidator';

    const STORED_PROCEDURE_VALIDATOR = 'Siesta\Contract\StoredProcedureValidator';

    const VALUE_OBJECT_VALIDATOR = 'Siesta\Contract\ValueObjectValidator';

    /**
     * @var DataModelValidator[]
     */
    protected array $dataModelValidatorList;

    /**
     * @var EntityValidator[]
     */
    protected array $entityValidatorList;

    /**
     * @var AttributeValidator[]
     */
    protected array $attributeValidatorList;

    /**
     * @var ReferenceValidator[]
     */
    protected array $referenceValidatorList;

    /**
     * @var IndexValidator[]
     */
    protected array $indexValidatorList;

    /***
     * @var CollectionValidator[]
     */
    protected array $collectionValidatorList;

    /**
     * @var CollectionManyValidator[]
     */
    protected array $collectionManyValidatorList;

    /**
     * @var DynamicCollectionValidator[]
     */
    protected array $dynamicCollectionValidatorList;

    /**
     * @var StoredProcedureValidator[]
     */
    protected array $storedProcedureValidatorList;

    /**
     * @var ValueObjectValidator[]
     */
    protected array $valueObjectValidatorList;

    /**
     * Validator constructor.
     */
    public function __construct()
    {
        $this->dataModelValidatorList = [];
        $this->entityValidatorList = [];
        $this->attributeValidatorList = [];
        $this->referenceValidatorList = [];
        $this->indexValidatorList = [];
        $this->collectionValidatorList = [];
        $this->collectionManyValidatorList = [];
        $this->dynamicCollectionValidatorList = [];
        $this->storedProcedureValidatorList = [];
        $this->valueObjectValidatorList = [];
    }

    /**
     * @param GenericGeneratorConfig[] $genericGeneratorConfigList
     * @throws ReflectionException
     */
    public function setup(array $genericGeneratorConfigList): void
    {
        foreach ($genericGeneratorConfigList as $genericGeneratorConfig) {
            $this->addGenericGeneratorConfig($genericGeneratorConfig);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param ValidationLogger $logger
     */
    public function validateDataModel(DataModel $dataModel, ValidationLogger $logger): void
    {
        foreach ($this->dataModelValidatorList as $dataModelValidator) {
            $dataModelValidator->validate($dataModel, $logger);
        }
        foreach ($dataModel->getEntityList() as $entity) {
            $this->validateEntity($dataModel, $entity, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValidationLogger $logger
     */
    protected function validateEntity(DataModel $dataModel, Entity $entity, ValidationLogger $logger): void
    {
        foreach ($this->entityValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $logger);
        }

        foreach ($entity->getAttributeList() as $attribute) {
            $this->validateAttribute($dataModel, $entity, $attribute, $logger);
        }

        foreach ($entity->getReferenceList() as $reference) {
            $this->validateReference($dataModel, $entity, $reference, $logger);
        }

        foreach ($entity->getIndexList() as $index) {
            $this->validateIndex($dataModel, $entity, $index, $logger);
        }

        foreach ($entity->getCollectionList() as $collection) {
            $this->validateCollection($dataModel, $entity, $collection, $logger);
        }

        foreach ($entity->getCollectionManyList() as $collectionMany) {
            $this->validateCollectionMany($dataModel, $entity, $collectionMany, $logger);
        }

        foreach($entity->getDynamicCollectionList() as $dynamicCollection) {
            $this->validateDynamicCollection($dataModel, $entity, $dynamicCollection, $logger);
        }

        foreach ($entity->getStoredProcedureList() as $storedProcedure) {
            $this->validateStoredProcedure($dataModel, $entity, $storedProcedure, $logger);
        }

        foreach ($entity->getValueObjectList() as $valueObject) {
            $this->validateValueObject($dataModel, $entity, $valueObject, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Attribute $attribute
     * @param ValidationLogger $logger
     */
    protected function validateAttribute(DataModel $dataModel, Entity $entity, Attribute $attribute, ValidationLogger $logger): void
    {
        foreach ($this->attributeValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $attribute, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     * @param ValidationLogger $logger
     */
    protected function validateReference(DataModel $dataModel, Entity $entity, Reference $reference, ValidationLogger $logger): void
    {
        foreach ($this->referenceValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $reference, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Index $index
     * @param ValidationLogger $logger
     */
    protected function validateIndex(DataModel $dataModel, Entity $entity, Index $index, ValidationLogger $logger): void
    {
        foreach ($this->indexValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $index, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Collection $collection
     * @param ValidationLogger $logger
     */
    protected function validateCollection(DataModel $dataModel, Entity $entity, Collection $collection, ValidationLogger $logger): void
    {
        foreach ($this->collectionValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $collection, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     * @param ValidationLogger $logger
     */
    protected function validateCollectionMany(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany, ValidationLogger $logger): void
    {
        foreach ($this->collectionManyValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $collectionMany, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param DynamicCollection $dynamicCollection
     * @param ValidationLogger $logger
     */
    protected function validateDynamicCollection(DataModel $dataModel, Entity $entity, DynamicCollection $dynamicCollection, ValidationLogger $logger): void
    {
        foreach ($this->dynamicCollectionValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $dynamicCollection, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param StoredProcedure $storedProcedure
     * @param ValidationLogger $logger
     */
    protected function validateStoredProcedure(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure, ValidationLogger $logger): void
    {
        foreach ($this->storedProcedureValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $storedProcedure, $logger);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValueObject $valueObject
     * @param ValidationLogger $logger
     */
    protected function validateValueObject(DataModel $dataModel, Entity $entity, ValueObject $valueObject, ValidationLogger $logger): void
    {
        foreach ($this->valueObjectValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $valueObject, $logger);
        }
    }

    /**
     * @param GenericGeneratorConfig $config
     * @throws ReflectionException
     */
    protected function addGenericGeneratorConfig(GenericGeneratorConfig $config): void
    {
        foreach ($config->getValidatorList() as $validatorClassName) {
            $this->addValidator($validatorClassName);
        }
    }

    /**
     * @param string $validatorClassName
     * @throws ReflectionException
     */
    protected function addValidator(string $validatorClassName): void
    {
        $reflect = new ReflectionClass($validatorClassName);

        $validator = new $validatorClassName;

        if ($reflect->implementsInterface(self::DATA_MODEL_VALIDATOR_INTERFACE)) {
            $this->dataModelValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::ENTITY_VALIDATOR_INTERFACE)) {
            $this->entityValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::ATTRIBUTE_VALIDATOR_INTERFACE)) {
            $this->attributeValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::REFERENCE_VALIDATOR_INTERFACE)) {
            $this->referenceValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::INDEX_VALIDATOR_INTERFACE)) {
            $this->indexValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::COLLECTION_VALIDATOR_INTERFACE)) {
            $this->collectionValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::COLLECTION_MANY_VALIDATOR_INTERFACE)) {
            $this->collectionManyValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::DYNAMIC_COLLECTION_VALIDATOR)) {
            $this->dynamicCollectionValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::STORED_PROCEDURE_VALIDATOR)) {
            $this->storedProcedureValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::VALUE_OBJECT_VALIDATOR)) {
            $this->valueObjectValidatorList[$validatorClassName] = $validator;
        }

    }

}