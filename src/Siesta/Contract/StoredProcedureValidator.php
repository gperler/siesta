<?php

namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\StoredProcedure;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface StoredProcedureValidator
{

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param StoredProcedure $storedProcedure
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, Entity $entity, StoredProcedure $storedProcedure, ValidationLogger $logger): void;

}