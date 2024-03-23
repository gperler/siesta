<?php
declare(strict_types = 1);

namespace Siesta\Validator;

use Siesta\Contract\CollectionValidator;
use Siesta\Model\Collection;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
class DefaultCollectionValidator implements CollectionValidator
{

    const ERROR_INVALID_NAME = "Entity '%s' collection with invalid name '%s' found.";

    const ERROR_INVALID_NAME_CODE = 1500;

    const ERROR_FOREIGN_TABLE = "Entity '%s' collection '%s' refers to unknown table '%s";

    const ERROR_FOREIGN_TABLE_CODE = 1501;

    const ERROR_FOREIGN_REFERENCE = "Entity '%s' collection '%s' refers to unknown reference '%s";

    const ERROR_FOREIGN_REFERENCE_CODE = 1502;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var Collection
     */
    protected Collection $collection;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Collection $collection
     * @param ValidationLogger $logger
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, Collection $collection, ValidationLogger $logger): void
    {
        $this->logger = $logger;
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->collection = $collection;

        $this->validateCollectionName();
        $this->validateForeignEntity();
        $this->validateForeignReference();
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
    protected function getCollectionName(): ?string
    {
        return $this->collection->getName();
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
        $collectionName = $this->getCollectionName();
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
        $foreignEntity = $this->collection->getForeignEntity();
        if ($foreignEntity !== null) {
            return;
        }
        $error = sprintf(self::ERROR_FOREIGN_TABLE, $this->getEntityName(), $this->getCollectionName(), $this->collection->getForeignTable());
        $this->error($error, self::ERROR_FOREIGN_TABLE_CODE);
    }

    /**
     *
     */
    protected function validateForeignReference(): void
    {
        $foreignReference = $this->collection->getForeignReference();
        if ($foreignReference !== null) {
            return;
        }

        $error = sprintf(self::ERROR_FOREIGN_REFERENCE, $this->getEntityName(), $this->getCollectionName(), $this->collection->getForeignReferenceName());
        $this->error($error, self::ERROR_FOREIGN_REFERENCE_CODE);
    }

}