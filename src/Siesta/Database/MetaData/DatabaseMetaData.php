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
    public function refresh(): void;

    /**
     * @return TableMetaData[]
     */
    public function getTableList() : array;

    /**
     * @param string $tableName
     *
     * @return TableMetaData|null
     */
    public function getTableByName(string $tableName): ?TableMetaData;

    /**
     * @return string[]
     */
    public function getStoredProcedureList() : array;

}