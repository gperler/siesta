<?php
declare(strict_types = 1);
namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface DatabaseMetaData
{

    /**
     * @return void
     */
    public function refresh();

    /**
     * @return TableMetaData[]
     */
    public function getTableList() : array;

    /**
     * @param string $tableName
     *
     * @return TableMetaData
     */
    public function getTableByName(string $tableName);

    /**
     * @return string[]
     */
    public function getStoredProcedureList() : array;

}