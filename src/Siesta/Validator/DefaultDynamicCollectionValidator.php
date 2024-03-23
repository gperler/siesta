<?php

declare(strict_types=1);

namespace Siesta\Validator;

use Siesta\Contract\DynamicCollectionValidator;
use Siesta\Model\DataModel;
use Siesta\Model\DynamicCollection;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

class DefaultDynamicCollectionValidator implements DynamicCollectionValidator
{
    const ERROR_INVALID_FOREIGN_TABLE = "Entity '%s' DynamicCollection '%s' points to not defined table %s";

    const ERROR_INVALID_FOREIGN_TABLE_CODE = 1900;

    const ERROR_INVALID_NAME_VALUE = "Entity '%s' DynamicCollection '%s' has invalid name";

    const ERROR_INVALID_NAME_VALUE_CODE = 1901;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var DynamicCollection
     */
    protected DynamicCollection $dynamicCollection;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

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
    protected function getName(): ?string
    {
        return $this->dynamicCollection->getName();
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param DynamicCollection $dynamicCollection
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, DynamicCollection $dynamicCollection, ValidationLogger $logger): void
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->dynamicCollection = $dynamicCollection;
        $this->logger = $logger;

        $this->validateForeignEntity();
        $this->validateName();
    }

    /**
     *
     */
    protected function validateForeignEntity(): void
    {
        if ($this->dynamicCollection->getForeignEntity() !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_FOREIGN_TABLE, $this->getEntityName(), $this->getName(), $this->dynamicCollection->getForeignTable());
        $this->error($error, self::ERROR_INVALID_FOREIGN_TABLE_CODE);
    }

    /**
     * @return void
     */
    protected function validateName(): void
    {
        if ($this->getName() !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_NAME_VALUE, $this->getEntityName(), $this->getName());
        $this->error($error, self::ERROR_INVALID_NAME_VALUE_CODE);
    }

}