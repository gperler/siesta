<?php
declare(strict_types=1);

namespace Siesta\Validator;

use Siesta\Contract\CollectionManyValidator;
use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
class DefaultCollectionManyValidator implements CollectionManyValidator
{

    const ERROR_INVALID_NAME = "Entity '%s' collectionMany with invalid name '%s' found.";

    const ERROR_INVALID_NAME_CODE = 1600;

    const ERROR_FOREIGN_TABLE = "Entity '%s' collectionMany '%s' refers to unknown table '%s";

    const ERROR_FOREIGN_TABLE_CODE = 1601;

    const ERROR_MAPPING_TABLE = "Entity '%s' collectionMany '%s' refers to unknown mapping table '%s";

    const ERROR_MAPPING_TABLE_CODE = 1602;

    const ERROR_FOREIGN_REFERENCE = "Entity '%s' collectionMany '%s' mapping table '%s' has no reference to %s";

    const ERROR_FOREIGN_REFERENCE_CODE = 1603;

    const ERROR_MAPPING_REFERENCE = "Entity '%s' collectionMany '%s' mapping table '%s' has no reference to %s";

    const ERROR_MAPPING_REFERENCE_CODE = 1604;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var CollectionMany
     */
    protected CollectionMany $collectionMany;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany, ValidationLogger $logger): void
    {
        $this->logger = $logger;
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->collectionMany = $collectionMany;

        $this->validateCollectionName();
        $this->validateForeignEntity();
        $this->validateMappingEntity();
        $this->validateReferences();
    }

    /**
     * @return string|null
     */
    protected function getEntityName(): ?string
    {
        return $this->entity->getClassShortName();
    }

    /**
     * @return string|null
     */
    protected function getCollectionManyName(): ?string
    {
        return $this->collectionMany->getName();
    }

    /**
     * @param string $text
     * @param int $code
     */
    protected function error(string $text, int $code): void
    {
        $this->logger->error($text, $code);
    }

    /**
     *
     */
    protected function validateCollectionName(): void
    {
        $collectionName = $this->getCollectionManyName();
        if ($collectionName !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_NAME, $this->getEntityName(), $collectionName);
        $this->error($error, self::ERROR_INVALID_NAME_CODE);
    }

    /**
     *
     */
    protected function validateForeignEntity(): void
    {
        $foreignEntity = $this->collectionMany->getForeignEntity();
        if ($foreignEntity !== null) {
            return;
        }
        $error = sprintf(self::ERROR_FOREIGN_TABLE, $this->getEntityName(), $this->getCollectionManyName(), $this->collectionMany->getForeignTable());
        $this->error($error, self::ERROR_FOREIGN_TABLE_CODE);
    }

    /**
     *
     */
    protected function validateMappingEntity(): void
    {
        $mappingEntity = $this->collectionMany->getMappingEntity();
        if ($mappingEntity !== null) {
            return;
        }

        $error = sprintf(self::ERROR_MAPPING_TABLE, $this->getEntityName(), $this->getCollectionManyName(), $this->collectionMany->getMappingTable());
        $this->error($error, self::ERROR_MAPPING_TABLE_CODE);
    }

    /**
     *
     */
    protected function validateReferences(): void
    {
        $foreignReference = $this->collectionMany->getForeignReference();
        if ($foreignReference === null) {
            $error = sprintf(self::ERROR_FOREIGN_REFERENCE, $this->getEntityName(), $this->getCollectionManyName(), $this->collectionMany->getMappingTable(), $this->collectionMany->getForeignTable());
            $this->error($error, self::ERROR_FOREIGN_REFERENCE_CODE);
        }

        $mappingReference = $this->collectionMany->getMappingReference();
        if ($mappingReference === null) {
            $error = sprintf(self::ERROR_MAPPING_REFERENCE, $this->getEntityName(), $this->getCollectionManyName(), $this->collectionMany->getMappingTable(), $this->getEntityName());
            $this->error($error, self::ERROR_MAPPING_REFERENCE_CODE);
        }
    }

}