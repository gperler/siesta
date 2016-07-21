<?php

namespace SiestaTest\TestDatabase;

use Siesta\Database\StoredProcedureDefinition;

class TestStoredProcedureDefinition implements StoredProcedureDefinition
{

    /**
     * @var
     */
    protected $dropStatement;

    /**
     * @var
     */
    protected $createStatement;

    /**
     * TestStoredProcedureDefinition constructor.
     *
     * @param string $dropStatement
     * @param string $createStatement
     */
    public function __construct($dropStatement = null, $createStatement = null)
    {
        $this->dropStatement = $dropStatement;
        $this->createStatement = $createStatement;
    }

    /**
     * @return null|string
     */
    public function getDropProcedureStatement()
    {
        return $this->dropStatement;
    }

    /**
     * @return null|string
     */
    public function getCreateProcedureStatement()
    {
        return $this->createStatement;
    }

}