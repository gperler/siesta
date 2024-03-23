<?php

declare(strict_types = 1);

namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;
use Siesta\Model\ValueObject;

interface ValueObjectValidator
{

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValueObject $valueObject
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, ValueObject $valueObject, ValidationLogger $logger): void;

}