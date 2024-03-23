<?php

declare(strict_types=1);

namespace SiestaTest\TestDatabase\MetaData;

use Siesta\Database\StoredProcedureDefinition;
use Siesta\Util\ArrayUtil;

class TestStoredProcedureDefinition implements StoredProcedureDefinition
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $createStatement;

    /**
     * @var string
     */
    private string $dropStatement;

    /**
     * TestStoredProcedureDefinition constructor.
     * @param array $storedProcedureData
     */
    public function __construct(array $storedProcedureData)
    {
        $this->name = ArrayUtil::getFromArray($storedProcedureData, "name");
        $this->createStatement = ArrayUtil::getFromArray($storedProcedureData, "createStatement");
        $this->dropStatement = ArrayUtil::getFromArray($storedProcedureData, "dropStatement");
    }


    /**
     * @return string|null
     */
    public function getDropProcedureStatement(): ?string
    {
        return $this->dropStatement;
    }

    /**
     * @return string|null
     */
    public function getCreateProcedureStatement(): ?string
    {
        return $this->createStatement;
    }

    /**
     * @return string
     */
    public function getProcedureName(): string
    {
        return $this->name;
    }


}