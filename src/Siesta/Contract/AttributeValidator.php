<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface AttributeValidator
{
    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Attribute $attribute
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, Attribute $attribute, ValidationLogger $logger): void;
}