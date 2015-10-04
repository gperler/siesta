<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 01.07.15
 * Time: 14:05
 */

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\driver\mysqli\MySQLDriver;

/**
 * Class TableMetadata
 * @package siestaphp\driver\mysqli\metadata
 */
class TableMetadata implements EntitySource
{

    const FIND_COLUMNS = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME='%s';";

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var MySQLDriver
     */
    protected $driver;

    /**
     * @var AttributeMetaData[]
     */
    protected $attributeMetaData;

    /**
     * @param MySQLDriver $driver
     * @param string $tableName
     */
    public function __construct(MySQLDriver $driver, $tableName)
    {
        $this->tableName = $tableName;
        $this->driver = $driver;

        $this->attributeMetaData = array();
        $this->extractColumns();
    }

    protected function extractColumns()
    {

        $sql = sprintf(self::FIND_COLUMNS, $this->driver->getDatabase(), $this->tableName);

        $resultSet = $this->driver->query($sql);

        while ($resultSet->hasNext()) {
            $columnKey = $resultSet->getStringValue(AttributeMetaData::COLUMN_KEY) . PHP_EOL;
            if ($columnKey !== AttributeMetaData::COLUMN_KEY_FOREIGN_KEY) {
                $this->attributeMetaData[] = new AttributeMetaData($resultSet);
            }
        }

    }

    /**
     * @return AttributeSource[]
     */
    public function getAttributeSourceList()
    {
        // TODO: Implement getAttributeSourceList() method.
    }

    /**
     * @return ReferenceSource[]
     */
    public function getReferenceSourceList()
    {
        // TODO: Implement getReferenceSourceList() method.
    }

    /**
     * @return StoredProcedureSource[]
     */
    public function getStoredProcedureSourceList()
    {
        return array();
    }

    /**
     * @return CollectorSource[]
     */
    public function getCollectorSourceList()
    {
        return array();
    }

    /**
     * @return IndexSource[]
     */
    public function getIndexSourceList()
    {
        // TODO: Implement getIndexSourceList() method.
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        // TODO: Implement getClassName() method.
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
        // TODO: Implement getClassNamespace() method.
    }

    /**
     * @return string
     */
    public function getConstructorClass()
    {
        // TODO: Implement getConstructorClass() method.
    }

    /**
     * @return string
     */
    public function getConstructorNamespace()
    {
        // TODO: Implement getConstructorNamespace() method.
    }

    /**
     * @return string
     */
    public function getTable()
    {
        // TODO: Implement getTable() method.
    }

    /**
     * @return boolean
     */
    public function isDelimit()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        // TODO: Implement getTargetPath() method.
    }

}