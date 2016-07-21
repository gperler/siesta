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
    public function getColumnByName(string $name);

    /**
     * @return ConstraintMetaData[]
     */
    public function getConstraintList() : array;

    /**
     * @return ConstraintMetaData|null
     */
    public function getConstraintByName(string $name);

    /**
     * @return IndexMetaData[]
     */
    public function getIndexList() : array;

    /**
     * @param string $name
     *
     * @return IndexMetaData|null
     */
    public function getIndexByName(string $name);

    /**
     * @return ColumnMetaData[]
     */
    public function getPrimaryKeyAttributeList() : array;

    /**
     * @return array
     */
    public function getDataBaseSpecific() : array;

}