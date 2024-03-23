<?php
declare(strict_types=1);

namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\DynamicCollection;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface DynamicCollectionValidator
{
    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param DynamicCollection $dynamicCollection
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, DynamicCollection $dynamicCollection, ValidationLogger $logger): void;

}