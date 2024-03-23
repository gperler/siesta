<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface EntityValidator
{
    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, ValidationLogger $logger): void;

}