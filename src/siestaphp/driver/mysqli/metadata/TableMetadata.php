<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\datamodel\collector\CollectorSource;
use siestaphp\datamodel\DatabaseSpecificSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\datamodel\index\IndexSource;
use siestaphp\datamodel\manager\EntityManager;
use siestaphp\datamodel\manager\EntityManagerSource;
use siestaphp\datamodel\reference\ReferenceSource;
use siestaphp\datamodel\storedprocedure\StoredProcedureSource;
use siestaphp\driver\Connection;
use siestaphp\naming\NamingService;

/**
 * Class TableMetadata
 * @package siestaphp\driver\mysqli\metadata
 */
class TableMetadata implements EntitySource
{
    const SQL_GET_COLUMN_DETAILS = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s';";

    const SQL_GET_KEY_COLUMN_USAGE = "SELECT * FROM information_schema.key_column_usage as KC WHERE KC.CONSTRAINT_SCHEMA = '%s' AND KC.TABLE_NAME = '%s'";

    const SQL_GET_REFERENTIAL_CONSTRAINTS = "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS AS RC WHERE RC.CONSTRAINT_SCHEMA = '%s' AND RC.TABLE_NAME = '%s'";

    const SQL_GET_INDEX_LIST = "SELECT S.* FROM INFORMATION_SCHEMA.STATISTICS AS S WHERE S.TABLE_SCHEMA = '%s' AND S.TABLE_NAME = '%s';";

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
     * @var Connection
     */
    protected $connection;

    /**
     * @var AttributeMetaData[]
     */
    protected $attributeMetaDataList;

    /**
     * @var ReferenceMetaData[]
     */
    protected $referenceMetaDataList;

    /**
     * @var IndexMetaData[]
     */
    protected $indexMetaList;

    /**
     * @param Connection $connection
     * @param TableDTO $tableDTO
     * @param string $targetNamespace
     * @param string $targetPath
     */
    public function __construct(Connection $connection, TableDTO $tableDTO, $targetNamespace, $targetPath )
    {
        $this->tableName = $tableDTO->name;
        $this->targetPath = $targetPath;
        $this->targetNamespace = $targetNamespace;

        $this->connection = $connection;

        $this->attributeMetaDataList = array();
        $this->referenceMetaDataList = array();
        $this->indexMetaList = array();

        $this->extractReferenceData();

        $this->extractColumns();

        $this->extractIndexData();
    }

    /**
     * reads foreign key constraints and enriches ReferenceMetaData objects
     */
    protected function extractReferenceData()
    {
        $sql = sprintf(self::SQL_GET_KEY_COLUMN_USAGE, $this->connection->getDatabase(), $this->tableName);

        $resultSet = $this->connection->query($sql);

        while ($resultSet->hasNext()) {

            // do not consider primary keys constraints here
            if (!ReferenceMetaData::considerConstraints($resultSet)) {
                continue;
            }

            // get constraint name to see, if we already have a constraint
            $constraintName = ReferenceMetaData::getConstraintNameFromResultSet($resultSet);

            $referenceMetaData = $this->getReferenceByConstraintName($constraintName);
            if ($referenceMetaData) {
                $referenceMetaData->updateFromConstraint($resultSet);
            } else {
                $this->referenceMetaDataList[] = new ReferenceMetaData($resultSet);
            }
        }

        $resultSet->close();

        $sql = sprintf(self::SQL_GET_REFERENTIAL_CONSTRAINTS, $this->connection->getDatabase(), $this->tableName);
        $resultSet = $this->connection->query($sql);

        while ($resultSet->hasNext()) {
            $constraintName = ReferenceMetaData::getConstraintNameFromResultSet($resultSet);
            $referenceMetaData = $this->getReferenceByConstraintName($constraintName);

            if ($referenceMetaData) {
                $referenceMetaData->updateConstraintData($resultSet);
            }

        }
        $resultSet->close();

    }

    /**
     * extracts columns from table and create AttributeMetaData or ReferenceMetaData objects
     */
    protected function extractColumns()
    {

        $sql = sprintf(self::SQL_GET_COLUMN_DETAILS, $this->connection->getDatabase(), $this->tableName);

        $resultSet = $this->connection->executeStoredProcedure($sql);

        while ($resultSet->hasNext()) {
            $columnName = $resultSet->getStringValue(AttributeMetaData::COLUMN_NAME);
            $reference = $this->getReferenceByColumnName($columnName);
            if ($reference !== null) {
                $reference->updateFromColumn($resultSet);
            } else {
                $this->attributeMetaDataList[] = new AttributeMetaData($resultSet);
            }
        }

        $resultSet->close();
    }

    /**
     * @param $constraintName
     *
     * @return null|ReferenceMetaData
     */
    private function getReferenceByConstraintName($constraintName)
    {
        foreach ($this->referenceMetaDataList as $referenceMetaData) {
            if ($referenceMetaData->getConstraintName() === $constraintName) {
                return $referenceMetaData;
            }
        }
        return null;
    }

    /**
     * retrieves a ReferenceMetaData by column name
     *
     * @param string $columnName
     *
     * @return ReferenceMetaData
     */
    protected function getReferenceByColumnName($columnName)
    {
        foreach ($this->referenceMetaDataList as $referenceMetaData) {
            if ($referenceMetaData->usesColumn($columnName)) {
                return $referenceMetaData;
            }
        }
        return null;
    }

    /**
     * extracts index data
     */
    protected function extractIndexData()
    {
        $sql = sprintf(self::SQL_GET_INDEX_LIST, $this->connection->getDatabase(), $this->tableName);

        $resultSet = $this->connection->query($sql);
        while ($resultSet->hasNext()) {

            // do not handle primary key indexes
            if (!IndexMetaData::isValidIndex($resultSet)) {
                continue;
            }

            $indexName = IndexMetaData::getIndexNameFromResultSet($resultSet);

            // do not handle indexes that are created for constraints
            $constraint = $this->getReferenceByConstraintName($indexName);
            if ($constraint) {
                continue;
            }

            // check if there is already an existing index
            $index = $this->getIndexByName($indexName);
            if ($index) {
                // if the index already exists add this information about a part of the index
                $index->addIndexPart($resultSet);
            } else {
                // create a new Index
                $this->indexMetaList[] = new IndexMetaData($resultSet, $this->attributeMetaDataList, $this->referenceMetaDataList);
            }
        }
    }

    /**
     * @param $indexName
     *
     * @return null|IndexMetaData
     */
    protected function getIndexByName($indexName)
    {
        foreach ($this->indexMetaList as $indexMetaData) {
            if ($indexMetaData->getName() === $indexName) {
                return $indexMetaData;
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
        return $this->indexMetaList;
    }

    /**
     * @return EntityManagerSource
     */
    public function getEntityManagerSource()
    {
        return new EntityManager($this, null);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return NamingService::getClassName($this->tableName);
    }

    /**
     * @return string
     */
    public function getClassNamespace()
    {
        return $this->targetNamespace;
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
        return "sql/gen2";
    }

    /**
     * @param string $database
     *
     * @return DatabaseSpecificSource
     */
    public function getDatabaseSpecific($database)
    {
        return null;
    }

}
