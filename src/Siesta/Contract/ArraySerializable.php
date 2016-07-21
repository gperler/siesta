<?php

declare(strict_types = 1);

namespace Siesta\Contract;

interface ArraySerializable
{
    /**
     * @param array|null $data
     *
     * @return void
     */
    public function fromArray(array $data);

    /**
     * @return void
     */
    public function toArray();
}