<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\CollectionMany;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface CollectionManyValidator
{
    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param CollectionMany $collectionMany
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, CollectionMany $collectionMany, ValidationLogger $logger): void;

}