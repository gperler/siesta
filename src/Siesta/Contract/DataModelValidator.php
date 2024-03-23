<?php
declare(strict_types = 1);
namespace Siesta\Contract;

use Siesta\Model\DataModel;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
interface DataModelValidator
{
    /**
     * @param DataModel $dataModel
     * @param ValidationLogger $logger
     *
     * @return void
     */
    public function validate(DataModel $dataModel, ValidationLogger $logger): void;
}
