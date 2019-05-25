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
    private $name;

    /**
     * @var string
     */
    private $createStatement;

    /**
     * @var string
     */
    private $dropStatement;

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

    /**
     * @return string
     */
    public function getProcedureName(): string
    {
        return $this->name;
    }


}