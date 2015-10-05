<?php

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\driver\ResultSet;

/**
 * Class AttributeMetaData
 * @package siestaphp\driver\mysqli\metadata
 */
class AttributeMetaData implements AttributeSource
{

    const COLUMN_NAME = "COLUMN_NAME";

    const COLUMN_TYPE = "COLUMN_TYPE";

    const COLUMN_KEY = "COLUMN_KEY";
    const COLUMN_KEY_PRIMARY_KEY = "PRI";
    const COLUMN_KEY_FOREIGN_KEY = "MUL";

    const COLUMN_DEFAULT = "COLUMN_DEFAULT";

    const COLUMN_IS_NULLABLE = "IS_NULLABLE";
    const COLUMN_IS_NULLABLE_YES = "YES";

    const DATA_TYPE = "DATA_TYPE";

    protected $name;
    protected $type;
    protected $dataType;
    protected $default;
    protected $isPrimaryKey;
    protected $isNullAble;

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->name = $resultSet->getStringValue(self::COLUMN_NAME);
        $this->type = $resultSet->getStringValue(self::COLUMN_TYPE);
        $this->isPrimaryKey = $resultSet->getStringValue(self::COLUMN_KEY) === self::COLUMN_KEY_PRIMARY_KEY;
        $this->default = $resultSet->getStringValue(self::COLUMN_DEFAULT);
        $this->isNullAble = $resultSet->getStringValue(self::COLUMN_IS_NULLABLE) === self::COLUMN_IS_NULLABLE_YES;
        $this->dataType = strtolower($resultSet->getStringValue(self::DATA_TYPE));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strtolower($this->name);
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        switch ($this->dataType) {
            case 'bit':
            case 'smallint':
                return 'bool';

            case 'mediumint':
            case 'int':
                return 'int';

            case 'double':
            case 'float':
            case 'decimal':
                return 'float';

            case 'date':
            case 'time':
            case 'datetime':
                return 'DateTime';

            default:
                return 'string';

        }
    }

    /**
     * @return string
     */
    public function getAutoValue()
    {
        // TODO: Implement getAutoValue() method.
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return strtoupper($this->name);
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->default;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->isPrimaryKey;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return !$this->isNullAble;
    }

}