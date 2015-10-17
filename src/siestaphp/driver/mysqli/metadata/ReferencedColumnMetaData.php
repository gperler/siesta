<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 15.10.15
 * Time: 00:37
 */

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\reference\ReferencedColumnSource;
use siestaphp\driver\ResultSet;

/**
 * Class ReferencedColumnMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class ReferencedColumnMetaData implements ReferencedColumnSource
{

    /**
     * @var string
     */
    protected $columnName;

    /**
     * @var string
     */
    protected $foreignTable;

    /**
     * @var string
     */
    protected $foreignColumn;
    /**
     * @var AttributeMetaData
     */
    protected $attributeColumn;

    /**
     * @param $columnName
     * @param $foreignTable
     * @param $foreignColumn
     */
    public function __construct($columnName, $foreignTable, $foreignColumn)
    {
        $this->columnName = $columnName;
        $this->foreignTable = $foreignTable;
        $this->foreignColumn = $foreignColumn;

    }

    /**
     * @param ResultSet $resultSet
     */
    public function updateFromColumn(ResultSet $resultSet)
    {
        $this->attributeColumn = new AttributeMetaData($resultSet);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getDatabaseName()
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        if (!$this->attributeColumn) {
            return null;
        }
        return $this->attributeColumn->getDatabaseType();
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        if (!$this->attributeColumn) {
            return null;
        }
        return $this->attributeColumn->isPrimaryKey();
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        if (!$this->attributeColumn) {
            return null;
        }
        return $this->attributeColumn->isRequired();
    }

    /**
     * @return string
     */
    public function getSQLParameterName()
    {
        if (!$this->attributeColumn) {
            return null;
        }
        return $this->attributeColumn->getSQLParameterName();
    }

    /**
     * @return string
     */
    public function getReferencedColumnMethodName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getReferencedDatabaseName()
    {
        return $this->foreignColumn;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return null;
    }

}