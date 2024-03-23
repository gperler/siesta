<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\Collection;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface CollectionValidator
{

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Collection $collection
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, Collection $collection, ValidationLogger $logger): void;

}