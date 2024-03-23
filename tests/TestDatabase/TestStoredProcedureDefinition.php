<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\StoredProcedureDefinition;

class TestStoredProcedureDefinition implements StoredProcedureDefinition
{

    /**
     * @var string
     */
    private ?string $name;

    /**
     * @var string|null
     */
    private ?string $dropStatement;

    /**
     * @var string|null
     */
    private ?string $createStatement;

    /**
     * TestStoredProcedureDefinition constructor.
     * @param string $name
     * @param string|null $dropStatement
     * @param string|null $createStatement
     */
    public function __construct(string $name = "", string $dropStatement = null, string $createStatement = null)
    {
        $this->name = $name;
        $this->dropStatement = $dropStatement;
        $this->createStatement = $createStatement;
    }

    /**
     * @return null|string
     */
    public function getDropProcedureStatement(): ?string
    {
        return $this->dropStatement;
    }

    /**
     * @return null|string
     */
    public function getCreateProcedureStatement(): ?string
    {
        return $this->createStatement;
    }

    /**
     * @return string
     */
    public function getProcedureName() : string
    {
        return $this->name;
    }

}