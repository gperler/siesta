<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface IndexValidator
{
    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Index $index
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, Index $index, ValidationLogger $logger): void;
}