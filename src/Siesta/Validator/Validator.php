<?php
declare(strict_types = 1);

namespace Siesta\Validator;

use Siesta\Config\GenericGeneratorConfig;
use Siesta\Contract\AttributeValidator;
use Siesta\Contract\CollectionManyValidator;
use Siesta\Contract\CollectionValidator;
use Siesta\Contract\DataModelValidator;
use Siesta\Contract\EntityValidator;
use Siesta\Contract\IndexValidator;
use Siesta\Contract\ReferenceValidator;
use Siesta\Model\Attribute;
use Siesta\Model\Collection;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\Reference;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
class Validator
{

    const DATAMODEL_VALIDATOR_INTERFACE = 'Siesta\Contract\DataModelValidator';

    const ENTIY_VALIDATOR_INTERFACE = 'Siesta\Contract\EntityValidator';

    const ATTRIBUTE_VALIDATOR_INTERFACE = 'Siesta\Contract\AttributeValidator';

    const REFERENCE_VALIDATOR_INTERFACE = 'Siesta\Contract\ReferenceValidator';

    const INDEX_VALIDATOR_INTERFACE = 'Siesta\Contract\IndexValidator';

    const COLLECTION_VALIDATOR_INTERFACE = 'Siesta\Contract\CollectionValidator';

    const COLLECTION_MANY_VALIDATOR_INTERFACE = 'Siesta\Contract\CollectionManyValidator';

    /**
     * @var DataModelValidator[]
     */
    protected $dataModelValidatorList;

    /**
     * @var EntityValidator[]
     */
    protected $entityValidatorList;

    /**
     * @var AttributeValidator[]
     */
    protected $attributeValidatorList;

    /**
     * @var ReferenceValidator[]
     */
    protected $referenceValidatorList;

    /**
     * @var IndexValidator[]
     */
    protected $indexValidatorList;

    /***
     * @var CollectionValidator[]
     */
    protected $collectionValidatorList;

    /**
     * @var CollectionManyValidator[]
     */
    protected $collectionManyValidatorList;

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
    }

    /**
     * @param GenericGeneratorConfig[] $genericGeneratorConfigList
     */
    public function setup(array $genericGeneratorConfigList)
    {
        foreach ($genericGeneratorConfigList as $genericGeneratorConfig) {
            $this->addGenericGeneratorConfig($genericGeneratorConfig);
        }
    }

    /**
     * @param DataModel $dataModel
     * @param ValidationLogger $logger
     */
    public function validateDataModel(DataModel $dataModel, ValidationLogger $logger)
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
    protected function validateEntity(DataModel $dataModel, Entity $entity, ValidationLogger $logger)
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
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Attribute $attribute
     * @param ValidationLogger $logger
     */
    protected function validateAttribute(DataModel $dataModel, Entity $entity, Attribute $attribute, ValidationLogger $logger)
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
    protected function validateReference(DataModel $dataModel, Entity $entity, Reference $reference, ValidationLogger $logger)
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
    protected function validateIndex(DataModel $dataModel, Entity $entity, Index $index, ValidationLogger $logger)
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
    protected function validateCollection(DataModel $dataModel, Entity $entity, Collection $collection, ValidationLogger $logger)
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
    protected function validateCollectionMany(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany, ValidationLogger $logger)
    {
        foreach ($this->collectionManyValidatorList as $validator) {
            $validator->validate($dataModel, $entity, $collectionMany, $logger);
        }
    }

    /**
     * @param GenericGeneratorConfig $config
     */
    protected function addGenericGeneratorConfig(GenericGeneratorConfig $config)
    {
        foreach ($config->getValidatorList() as $validatorClassName) {
            $this->addValidator($validatorClassName);
        }
    }

    /**
     * @param string $validatorClassName
     */
    protected function addValidator(string $validatorClassName)
    {
        $reflect = new \ReflectionClass($validatorClassName);

        $validator = new $validatorClassName;

        if ($reflect->implementsInterface(self::DATAMODEL_VALIDATOR_INTERFACE)) {
            $this->dataModelValidatorList[$validatorClassName] = $validator;
        }

        if ($reflect->implementsInterface(self::ENTIY_VALIDATOR_INTERFACE)) {
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

    }

}