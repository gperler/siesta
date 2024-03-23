<?php
declare(strict_types = 1);
namespace Siesta\Database;

interface StoredProcedureDefinition
{

    /**
     * @return string|null
     */
    public function getDropProcedureStatement(): ?string;

    /**
     * @return string|null
     */
    public function getCreateProcedureStatement(): ?string;

    /**
     * @return string
     */
    public function getProcedureName() : string;

}