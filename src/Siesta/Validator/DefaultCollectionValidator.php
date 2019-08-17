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
    protected $dataModel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var ValidationLogger
     */
    protected $logger;

    public function validate(DataModel $dataModel, Entity $entity, Collection $collection, ValidationLogger $logger)
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
     * @return string
     */
    protected function getEntityName()
    {
        return $this->entity->getClassShortName();
    }

    /**
     * @return string
     */
    protected function getCollectionName()
    {
        return $this->collection->getName();
    }

    /**
     * @param string $text
     * @param int $code
     */
    protected function error(string $text, int $code)
    {
        $this->logger->error($text, $code);
    }

    /**
     *
     */
    protected function validateCollectionName()
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
    protected function validateForeignEntity()
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
    protected function validateForeignReference()
    {
        $foreignReference = $this->collection->getForeignReference();
        if ($foreignReference !== null) {
            return;
        }

        $error = sprintf(self::ERROR_FOREIGN_REFERENCE, $this->getEntityName(), $this->getCollectionName(), $this->collection->getForeignReferenceName());
        $this->error($error, self::ERROR_FOREIGN_REFERENCE_CODE);
    }

}