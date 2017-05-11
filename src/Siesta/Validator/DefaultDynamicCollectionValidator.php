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

    const ERROR_INVALID_NAME_VALUD_CODE = 1901;

    /**
     * @var DataModel
     */
    protected $datamodel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var DynamicCollection
     */
    protected $dynamicCollection;

    /**
     * @var ValidationLogger
     */
    protected $logger;

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
    protected function getName()
    {
        return $this->dynamicCollection->getName();
    }

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param DynamicCollection $dynamicCollection
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, DynamicCollection $dynamicCollection, ValidationLogger $logger)
    {
        $this->datamodel = $dataModel;
        $this->entity = $entity;
        $this->dynamicCollection = $dynamicCollection;
        $this->logger = $logger;

        $this->validateForeignEntity();
        $this->validateName();
    }

    /**
     *
     */
    protected function validateForeignEntity()
    {
        if ($this->dynamicCollection->getForeignEntity() !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_FOREIGN_TABLE, $this->getEntityName(), $this->getName(), $this->dynamicCollection->getForeignTable());
        $this->error($error, self::ERROR_INVALID_FOREIGN_TABLE_CODE);
    }

    protected function validateName()
    {
        if ($this->getName() !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_NAME_VALUE, $this->getEntityName(), $this->getName());
        $this->error($error, self::ERROR_INVALID_NAME_VALUD_CODE);
    }

}