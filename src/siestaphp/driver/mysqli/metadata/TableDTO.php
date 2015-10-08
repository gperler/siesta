<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 08.10.15
 * Time: 22:06
 */

namespace siestaphp\driver\mysqli\metadata;

use siestaphp\driver\ResultSet;

/**
 * Class TableDTO
 * @package siestaphp\driver\mysqli\metadata
 */
class TableDTO
{

    const TABLE_NAME = "TABLE_NAME";

    const ENGINE = "ENGINE";

    const TABLE_COLLATION = "TABLE_COLLATION";

    public $name;
    public $engine;
    public $collation;

    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->name = $resultSet->getStringValue(self::TABLE_NAME);
        $this->engine = $resultSet->getStringValue(self::ENGINE);
        $this->collation = $resultSet->getStringValue(self::TABLE_COLLATION);
    }

}