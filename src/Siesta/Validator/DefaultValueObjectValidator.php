<?php

declare(strict_types = 1);

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
    protected $datamodel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var ValueObject
     */
    protected $valueObject;

    /**
     * @var ValidationLogger
     */
    protected $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValueObject $valueObject
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, ValueObject $valueObject, ValidationLogger $logger)
    {
        $this->datamodel = $dataModel;
        $this->entity = $entity;
        $this->valueObject = $valueObject;
        $this->logger = $logger;

        $this->validateNotNull();
        $this->validateClassExists();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return $this->entity->getClassShortName();
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
     * @return string
     */
    protected function getClassName()
    {
        return $this->valueObject->getClassName();
    }

    protected function validateNotNull()
    {
        $className = $this->getClassName();
        if ($className !== null) {
            return;
        }

        $error = sprintf(self::ERROR_INVALID_NAME, $this->getEntityName(), $className);
        $this->error($error, self::ERROR_INVALID_NAME_CODE);
    }

    protected function validateClassExists()
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