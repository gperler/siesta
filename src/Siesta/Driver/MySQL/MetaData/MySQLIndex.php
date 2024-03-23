<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\Database\ResultSet;

/**
 * @author Gregor Müller
 */
class MySQLIndex implements IndexMetaData
{

    const PRIMARY_KEY_INDEX_NAME = "PRIMARY";

    const INDEX_NAME = "INDEX_NAME";

    const NON_UNIQUE = "NON_UNIQUE";

    const SEQ_IN_INDEX = "SEQ_IN_INDEX";

    const NULLABLE = "NULLABLE";

    const NULLABLE_YES = "YES";

    const INDEX_TYPE = "INDEX_TYPE";

    /**
     * @var IndexPartMetaData[]
     */
    protected array $indexPartList;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var bool
     */
    protected bool $unique;

    /**
     * @var string
     */
    protected string $type;

    /**
     * MySQLIndex constructor.
     */
    public function __construct()
    {
        $this->indexPartList = [];
    }

    /**
     * @param ResultSet $resultSet
     */
    public function fromResultSet(ResultSet $resultSet): void
    {
        $this->name = $resultSet->getStringValue(self::INDEX_NAME);
        $this->unique = $resultSet->getIntegerValue(self::NON_UNIQUE) === 0;
        $this->type = $resultSet->getStringValue(self::INDEX_TYPE);
        $this->addIndexPart($resultSet);
    }

    /**
     * @param ResultSet $resultSet
     */
    public function addIndexPart(ResultSet $resultSet): void
    {
        $indexPart = new MySQLIndexPart();
        $indexPart->fromResultSet($resultSet);
        $this->indexPartList[] = $indexPart;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return strtolower($this->type);
    }

    /**
     * @return bool
     */
    public function getIsUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @return IndexPartMetaData[]
     */
    public function getIndexPartList(): array
    {
        return $this->indexPartList;
    }

}