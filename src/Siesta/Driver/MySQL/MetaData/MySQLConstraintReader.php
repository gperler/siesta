<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;

class MySQLConstraintReader
{

    const SQL_GET_KEY_COLUMN_USAGE = "SELECT * FROM information_schema.key_column_usage as KC WHERE KC.CONSTRAINT_SCHEMA = '%s'";

    const SQL_GET_REFERENTIAL_CONSTRAINTS = "SELECT * FROM information_schema.REFERENTIAL_CONSTRAINTS AS RC WHERE RC.CONSTRAINT_SCHEMA = '%s'";

    const COLUMN_TABLE_NAME = "TABLE_NAME";

    /**
     * @var Connection
     */
    private Connection $connection;


    /**
     * @var MySQLConstraint[][]
     */
    private array $constraintList;


    /**
     * MySQLColumnReader constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->constraintList = [];
        $this->readReferenceData();
        $this->readReferentialConstraints();
    }


    /**
     * @param string $tableName
     *
     * @return MySQLConstraint[]
     */
    public function getConstraintListByTableName(string $tableName): array
    {
        if (!isset($this->constraintList[$tableName])) {
            return [];
        }
        return $this->constraintList[$tableName];
    }


    /**
     *
     */
    protected function readReferenceData(): void
    {
        $sql = sprintf(self::SQL_GET_KEY_COLUMN_USAGE, $this->connection->getDatabase());

        $resultSet = $this->connection->query($sql);

        while ($resultSet->hasNext()) {
            // skip primary key index
            if ($resultSet->getStringValue(MySQLConstraint::REFERENCED_TABLE_NAME) === null) {
                continue;
            }

            $tableName = $resultSet->getStringValue(self::COLUMN_TABLE_NAME);

            $constraintName = $resultSet->getStringValue(MySQLConstraint::CONSTRAINT_NAME);
            $constraint = $this->getConstraintByName($tableName, $constraintName);

            if ($constraint === null) {
                $constraint = new MySQLConstraint();
                $constraint->fromResultSet($resultSet);
                $this->addConstraint($tableName, $constraint);
            } else {
                $constraint->addConstraint($resultSet);
            }
        }
        $resultSet->close();
    }


    /**
     *
     */
    private function readReferentialConstraints(): void
    {
        $sql = sprintf(self::SQL_GET_REFERENTIAL_CONSTRAINTS, $this->connection->getDatabase());
        $resultSet = $this->connection->query($sql);

        while ($resultSet->hasNext()) {
            $tableName = $resultSet->getStringValue(self::COLUMN_TABLE_NAME);
            $constraintName = $resultSet->getStringValue(MySQLConstraint::CONSTRAINT_NAME);

            $constraint = $this->getConstraintByName($tableName, $constraintName);

            if ($constraint !== null) {
                $constraint->addUpdateAndDeleteRule($resultSet);
            }
        }
        $resultSet->close();
    }


    /**
     * @param string $tableName
     * @param MySQLConstraint $constraint
     */
    private function addConstraint(string $tableName, MySQLConstraint $constraint): void
    {
        if (!isset($this->constraintList[$tableName])) {
            $this->constraintList[$tableName] = [];
        }
        $constraintName = $constraint->getConstraintName();
        $this->constraintList[$tableName][$constraintName] = $constraint;
    }


    /**
     * @param string $tableName
     * @param string $constraintName
     *
     * @return MySQLConstraint|null
     */
    public function getConstraintByName(string $tableName, string $constraintName): ?MySQLConstraint
    {
        if (!isset($this->constraintList[$tableName])) {
            return null;
        }
        if (!isset($this->constraintList[$tableName][$constraintName])) {
            return null;
        }
        return $this->constraintList[$tableName][$constraintName];
    }


}