<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface ReferenceValidator
{

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, Reference $reference, ValidationLogger $logger): void;

}