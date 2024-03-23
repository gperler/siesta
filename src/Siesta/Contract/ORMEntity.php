<?php
declare(strict_types = 1);
namespace Siesta\Contract;

interface ORMEntity
{
    /**
     * @param ORMEntity|null $entity
     * @return bool
     */
    public function arePrimaryKeyIdentical(ORMEntity $entity = null) : bool;
}