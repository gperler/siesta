<?php

declare(strict_types = 1);

namespace Siesta\Contract;

interface ArraySerializable
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function fromArray(array $data): void;

    /**
     * @return array|null
     */
    public function toArray(): ?array;
}