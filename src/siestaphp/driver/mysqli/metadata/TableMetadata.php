<?php


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
    const SP_GET_COLUMN_DETAILS = "CALL `SIESTA_GET_COLUMN_DETAILS` ('%s','%s')";
    const SP_GET_FK_DETAILS = "CALL `SIESTA_GET_FOREIGN_KEY_DETAILS` ('%s','%s')";

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * @var string
     */
    protected $targetNamespace;

    /**
     * @var MySQLDriver
     */
    protected $driver;

    /**
     * @var AttributeMetaData[]
     */
    protected $attributeMetaDataList;

    /**
     * @var ReferenceMetaData[]
     */
    protected $referenceMetaDataList;

    /**
     * @param MySQLDriver $driver
     * @param $tableName
     * @param $targetPath
     * @param $targetNamespace
     */
    public function __construct(MySQLDriver $driver, $tableName, $targetPath, $targetNamespace)
    {
        $this->tableName = $tableName;
        $this->driver = $driver;

        $this->attributeMetaDataList = array();
        $this->referenceMetaDataList = array();

        $this->extractReferenceData();

        $this->extractColumns();

    }

    /**
     * extracts columns from table and create AttributeMetaData or ReferenceMetaData objects
     */
    protected function extractColumns()
    {

        $sql = sprintf(self::SP_GET_COLUMN_DETAILS, $this->driver->getDatabase(), $this->tableName);

        $resultSet = $this->driver->executeStoredProcedure($sql);

        while ($resultSet->hasNext()) {
            $columnName = $resultSet->getStringValue(AttributeMetaData::COLUMN_NAME);
            $reference = $this->getReferenceByColumnName($columnName);
            if ($reference !== null) {
                $reference->updateReferenceInformation($resultSet);
            } else {
                $this->attributeMetaDataList[] = new AttributeMetaData($resultSet);
            }
        }

        $resultSet->close();
    }

    /**
     * reads foreign key constraints and enriches ReferenceMetaData objects
     */
    protected function extractReferenceData()
    {
        $sql = sprintf(self::SP_GET_FK_DETAILS, $this->driver->getDatabase(), $this->tableName);
        $resultSet = $this->driver->executeStoredProcedure($sql);

        while ($resultSet->hasNext()) {
            $this->referenceMetaDataList[] = new ReferenceMetaData($resultSet);
        }

        $resultSet->close();
    }

    /**
     * retrieves a ReferenceMetaData by column name
     * @param string $columnName
     *
     * @return ReferenceMetaData
     */
    protected function getReferenceByColumnName($columnName)
    {
        foreach ($this->referenceMetaDataList as $referenceMetaData) {
            if ($referenceMetaData->getName() === $columnName) {
                return $referenceMetaData;
            }
        }
        return null;
    }

    /**
     * @return AttributeSource[]
     */
    public function getAttributeSourceList()
    {
        return $this->attributeMetaDataList;
    }

    /**
     * @return ReferenceSource[]
     */
    public function getReferenceSourceList()
    {
        return $this->referenceMetaDataList;
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
        return ucfirst(strtolower($this->tableName));
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
    }

    /**
     * @return string
     */
    public function getConstructorClass()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getConstructorNamespace()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->tableName;
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