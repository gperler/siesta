<?php
declare(strict_types = 1);
namespace Siesta\Database\MetaData;

/**
 * @author Gregor Müller
 */
interface TableMetaData
{

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return ColumnMetaData[]
     */
    public function getColumnList() : array;

    /**
     * @param string $name
     *
     * @return ColumnMetaData|null
     */
    public function getColumnByName(string $name): ?ColumnMetaData;

    /**
     * @return ConstraintMetaData[]
     */
    public function getConstraintList() : array;

    /**
     * @param string $name
     * @return ConstraintMetaData|null
     */
    public function getConstraintByName(string $name): ?ConstraintMetaData;

    /**
     * @return IndexMetaData[]
     */
    public function getIndexList() : array;

    /**
     * @param string $indexName
     *
     * @return IndexMetaData|null
     */
    public function getIndexByName(string $indexName): ?IndexMetaData;

    /**
     * @return ColumnMetaData[]
     */
    public function getPrimaryKeyAttributeList() : array;

    /**
     * @return array
     */
    public function getDataBaseSpecific() : array;

}