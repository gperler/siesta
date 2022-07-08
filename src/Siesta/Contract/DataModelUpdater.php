<?php

declare(strict_types=1);

namespace Siesta\Contract;

use Siesta\Model\DataModel;

interface DataModelUpdater
{
    /**
     * @param DataModel $dataModel
     *
     * @return void
     */
    public function preUpdateModel(DataModel $dataModel): void;

    /**
     * @param DataModel $dateModel
     *
     * @return void
     */
    public function postUpdateModel(DataModel $dateModel): void;
}