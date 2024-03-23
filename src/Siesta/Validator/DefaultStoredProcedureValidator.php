<?php

namespace Siesta\Validator;

use Siesta\Contract\StoredProcedureValidator;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\StoredProcedure;
use Siesta\Model\ValidationLogger;

class DefaultStoredProcedureValidator implements StoredProcedureValidator
{

    const ERROR_INVALID_RESULT_TYPE = "Entity '%s' storedProcedure '%s' with invalid resultType '%s' found. Allowed values are %s";

    const ERROR_INVALID_RESULT_TYPE_CODE = 1700;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var StoredProcedure
     */
    protected StoredProcedure $storedProcedure;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param StoredProcedure $storedProcedure
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure, ValidationLogger $logger): void
    {
        $this->logger = $logger;
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->storedProcedure = $storedProcedure;

        $this->validateReturnType();
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
    protected function getStoredProcedureName(): ?string
    {
        return $this->storedProcedure->getName();
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
    protected function validateReturnType(): void
    {
        $resultType = $this->storedProcedure->getResultType();

        if (in_array($resultType, StoredProcedure::ALLOWED_RESULT)) {
            return;
        }

        $allowedValues = implode(",", StoredProcedure::ALLOWED_RESULT);

        $error = sprintf(self::ERROR_INVALID_RESULT_TYPE, $this->getEntityName(), $this->getStoredProcedureName(), $resultType, $allowedValues);
        $this->error($error, self::ERROR_INVALID_RESULT_TYPE_CODE);
    }

}