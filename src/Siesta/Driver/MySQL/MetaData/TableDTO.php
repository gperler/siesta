<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\ResultSet;

/**
 * @author Gregor Müller
 */
class TableDTO
{

    const TABLE_NAME = "TABLE_NAME";

    const ENGINE = "ENGINE";

    const TABLE_COLLATION = "TABLE_COLLATION";

    const AUTO_INCREMENT = "AUTO_INCREMENT";

    /**
     * @var string|null
     */
    public ?string $name;

    /**
     * @var string|null
     */
    public ?string $engine;

    /**
     * @var string|null
     */
    public ?string $collation;

    /**
     * @var bool|null
     */
    public ?bool $autoincrement;


    /**
     * @param ResultSet $resultSet
     */
    public function __construct(ResultSet $resultSet)
    {
        $this->name = $resultSet->getStringValue(self::TABLE_NAME);
        $this->engine = $resultSet->getStringValue(self::ENGINE);
        $this->collation = $resultSet->getStringValue(self::TABLE_COLLATION);
        $this->autoincrement = $resultSet->getBooleanValue(self::AUTO_INCREMENT);
    }

}