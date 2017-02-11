<?php
declare(strict_types = 1);
namespace Siesta\Database;

interface StoredProcedureDefinition
{

    /**
     * @return string
     */
    public function getDropProcedureStatement();

    /**
     * @return string
     */
    public function getCreateProcedureStatement();

    /**
     * @return string
     */
    public function getProcedureName() : string;

}