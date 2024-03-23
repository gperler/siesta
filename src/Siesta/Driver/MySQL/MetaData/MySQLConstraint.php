<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\Database\MetaData\ConstraintMetaData;
use Siesta\Database\ResultSet;
use Siesta\Util\StringUtil;

/**
 * @author Gregor Müller
 */
class MySQLConstraint implements ConstraintMetaData
{

    const COLUMN_NAME = "COLUMN_NAME";

    const CONSTRAINT_NAME = "CONSTRAINT_NAME";

    const REFERENCED_TABLE_NAME = "REFERENCED_TABLE_NAME";

    const REFERENCED_COLUMN_NAME = "REFERENCED_COLUMN_NAME";

    const DELETE_RULE = "DELETE_RULE";

    const UPDATE_RULE = "UPDATE_RULE";

    /**
     * @var string|null
     */
    protected ?string $constraintName;

    /**
     * @var string|null
     */
    protected ?string $foreignTable;

    /**
     * @var string|null
     */
    protected ?string $onUpdate;

    /**
     * @var string|null
     */
    protected ?string $onDelete;

    /**
     * @var MySQLConstraintMapping[]
     */
    protected array $constraintMappingList;


    /**
     * MySQLConstraint constructor.
     */
    public function __construct()
    {
        $this->constraintMappingList = [];
    }


    /**
     * @param ResultSet $resultSet
     */
    public function fromResultSet(ResultSet $resultSet): void
    {
        $this->constraintName = $resultSet->getStringValue(self::CONSTRAINT_NAME);
        $this->foreignTable = $resultSet->getStringValue(self::REFERENCED_TABLE_NAME);

        $this->addConstraint($resultSet);
    }


    /**
     * @param ResultSet $resultSet
     */
    public function addConstraint(ResultSet $resultSet): void
    {
        $constraintMapping = new MySQLConstraintMapping();
        $constraintMapping->fromResultSet($resultSet);
        $this->constraintMappingList[] = $constraintMapping;
    }


    /**
     * @param ResultSet $resultSet
     */
    public function addUpdateAndDeleteRule(ResultSet $resultSet): void
    {
        $this->onUpdate = $resultSet->getStringValue(self::UPDATE_RULE);
        $this->onDelete = $resultSet->getStringValue(self::DELETE_RULE);
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        $constraintName = $this->getConstraintName();
        if (strpos($constraintName, "_") !== false) {
            return StringUtil::getEndAfterLast($constraintName, "_");
        }
        return $constraintName;
    }


    public function getConstraintName(): string
    {
        return $this->constraintName;
    }


    /**
     * @return string
     */
    public function getForeignTable(): string
    {
        return $this->foreignTable;
    }


    /**
     * @return string
     */
    public function getOnUpdate(): string
    {
        return ConstraintRule::mySQLToSchema($this->onUpdate);
    }


    /**
     * @return string
     */
    public function getOnDelete(): string
    {
        return ConstraintRule::mySQLToSchema($this->onDelete);
    }


    /**
     * @return ConstraintMappingMetaData[]
     */
    public function getConstraintMappingList(): array
    {
        return $this->constraintMappingList;
    }

}