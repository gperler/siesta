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
        $this->type = strtoupper($resultSet->getStringValue(self::COLUMN_TYPE));
        $this->dataType = strtoupper($resultSet->getStringValue(self::DATA_TYPE));
        $this->isPrimaryKey = $resultSet->getStringValue(self::COLUMN_KEY) === self::COLUMN_KEY_PRIMARY_KEY;
        $this->default = $resultSet->getStringValue(self::COLUMN_DEFAULT);
        $this->isNullAble = $resultSet->getStringValue(self::COLUMN_IS_NULLABLE) === self::COLUMN_IS_NULLABLE_YES;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        switch ($this->dataType) {
            case 'BIT':
                return 'bool';

            case 'SMALLINT':
            case 'MEDIUMINT':
            case 'INT':
            case 'BIGINT':
                return 'int';

            case 'DOUBLE':
            case 'FLOAT':
            case 'DECIMAL':
                return 'float';

            case 'DATE':
            case 'TIME':
            case 'DATETIME':
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
        if ($this->isPrimaryKey) {
            return "autoincrement";
        }
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        switch ($this->type) {
            case "BIT(1)":
                return "BIT";
            case "SMALLINT(6)":
                return "SMALLINT";
            case "MEDIUMINT(9)":
                return "MEDIUMINT";
            case "INT(11)":
                return "INT";
            case "BIGINT(20)":
                return "BIGINT";
            case "DECIMAL(10,0)":
                return "DECIMAL";
            case "YEAR(4)":
                return "YEAR";
            default:
                return $this->type;
        }
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

    /**
     * @return bool
     */
    public function isTransient()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getSQLParameterName()
    {
        return null;
    }

}