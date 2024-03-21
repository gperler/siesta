<?php

declare(strict_types=1);

namespace Siesta\Validator;

use Siesta\Contract\ValueObjectValidator;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;
use Siesta\Model\ValueObject;

class DefaultValueObjectValidator implements ValueObjectValidator
{
    const ERROR_INVALID_NAME = "Entity '%s' ValueObject with invalid name '%s' found.";

    const ERROR_INVALID_NAME_CODE = 1800;

    const ERROR_CLASS_NOT_EXISTS = "Entity '%s' ValueObject class '%s' does not exist.";

    const ERROR_CLASS_NOT_EXISTS_CODE = 1801;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var ValueObject
     */
    protected ValueObject $valueObject;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValueObject $valueObject
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, ValueObject $valueObject, ValidationLogger $logger): void
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->valueObject = $valueObject;
        $this->logger = $logger;

        $this->validateNotNull();
        $this->validateClassExists();
    }

    /**
     * @return string|null
     */
    protected function getEntityName(): ?string
    {
        return $this->entity->getClassShortName();
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
     * @return string|null
     */
    protected function getClassName(): ?string
    {
        return $this->valueObject->getClassName();
    }

    /**
     * @return void
     */
    protected function validateNotNull(): void
    {
        $className = $this->getClassName();
        if ($className !== null) {
            return;
        }

        $error = sprintf(self::ERROR_INVALID_NAME, $this->getEntityName(), $className);
        $this->error($error, self::ERROR_INVALID_NAME_CODE);
    }

    /**
     * @return void
     */
    protected function validateClassExists(): void
    {
        $className = $this->getClassName();

        if ($className === null) {
            return;
        }

        if (class_exists($className)) {
            return;
        }

        $error = sprintf(self::ERROR_CLASS_NOT_EXISTS, $this->getEntityName(), $className);
        $this->error($error, self::ERROR_CLASS_NOT_EXISTS_CODE);
    }

}