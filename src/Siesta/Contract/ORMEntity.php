<?php
declare(strict_types = 1);
namespace Siesta\Contract;

interface ORMEntity
{
    public function arePrimaryKeyIdentical(ORMEntity $entity = null);
}