<?php

declare(strict_types=1);

namespace Siesta\Main;

use Siesta\Contract\DataModelUpdater;
use Siesta\Model\DataModel;

class NoUpdate implements DataModelUpdater
{
    public function preUpdateModel(DataModel $dataModel)
    {
    }

    public function postUpdateModel(DataModel $dateModel)
    {
    }

}