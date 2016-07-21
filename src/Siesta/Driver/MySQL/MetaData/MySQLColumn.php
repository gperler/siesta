<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\MetaData\ColumnMetaData;
use Siesta\Database\ResultSet;
use Siesta\Model\PHPType;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class MySQLColumn implements ColumnMetaData
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

    const PHP_TYPE_MAPPING = [
        "BIT" => "bool",
        "SMALLINT" => "int",
        "MEDIUMINT" => "int",
        "INT" => "int",
        "BIGINT" => "int",
        "DOUBLE" => "float",
        "FLOAT" => "float",
        "DECIMAL" => "float",
        "DATETIME" => "SiestaDateTime",
        "DATE" => "SiestaDateTime",
        "TIME" => "SiestaDateTime",
    ];

    const DB_TYPE_MAPPING = [
        "BIT(1)" => "BIT",
        "SMALLINT(6)" => "SMALLINT",
        "MEDIUMINT(9)" => "MEDIUMINT",
        "INT(11)" => "INT",
        "BIGINT(20)" => "BIGINT",
        "DECIMAL(10,0)" => "DECIMAL",
        "YEAR(4)" => "YEAR",
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $autovalue;

    /**
     * @var string
     */
    protected $columnType;

    /**
     * @var string
     */
    protected $dataType;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var bool
     */
    protected $isPrimaryKey;

    /**
     * @var bool
     */
    protected $isNullAble;



    /**
     * MySQLColumn constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param ResultSet $resultSet
     * @param bool|null $tableHasAutoincrement
     */
    public function fromResultSet(ResultSet $resultSet, bool $tableHasAutoincrement = null)
    {
        $this->name = $resultSet->getStringValue(self::COLUMN_NAME);
        $this->columnType = strtoupper($resultSet->getStringValue(self::COLUMN_TYPE));
        $this->dataType = strtoupper($resultSet->getStringValue(self::DATA_TYPE));
        $this->isPrimaryKey = $resultSet->getStringValue(self::COLUMN_KEY) === self::COLUMN_KEY_PRIMARY_KEY;
        $this->default = $resultSet->getStringValue(self::COLUMN_DEFAULT);
        $this->isNullAble = $resultSet->getStringValue(self::COLUMN_IS_NULLABLE) === self::COLUMN_IS_NULLABLE_YES;
        $this->autovalue = ($this->isPrimaryKey && $tableHasAutoincrement) ? 'autoincrement' : null;
    }

    /**
     * @return string
     */
    public function getDBType() : string
    {
        $dbType = ArrayUtil::getFromArray(self::DB_TYPE_MAPPING, $this->columnType);
        if ($dbType !== null) {
            return $dbType;
        }
        return $this->columnType;

    }

    /**
     * @return string
     */
    public function getDBName() : string
    {
        return $this->name;
    }

    /**
     * @param string $dbName
     */
    public function setDBName(string $dbName)
    {
        $this->name = $dbName;
    }

    /**
     * @return string
     */
    public function getPHPType() : string
    {
        $phpType = ArrayUtil::getFromArray(self::PHP_TYPE_MAPPING, $this->dataType);
        if ($phpType !== null) {
            return $phpType;
        }
        return PHPType::STRING;
    }

    /**
     * @return bool
     */
    public function getIsRequired() : bool
    {
        return !$this->isNullAble;
    }

    /**
     * @return bool
     */
    public function getIsPrimaryKey() : bool
    {
        return $this->isPrimaryKey;
    }

    /**
     * @return string
     */
    public function getAutoValue()
    {
        return $this->autovalue;
    }

}