<?php


namespace siestaphp\datamodel;

/**
 * Interface DatabaseColumn
 * @package siestaphp\datamodel
 */
interface DatabaseColumn {

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return string
     */
    public function getPHPType();

    /**
     * @return mixed
     */
    public function getDatabaseName();


    /**
     * @return string
     */
    public function getDatabaseType();

    /**
     * @return bool
     */
    public function isPrimaryKey();

    /**
     * @return bool
     */
    public function isRequired();


    /**
     * @return string
     */
    public function getSQLParameterName();
}