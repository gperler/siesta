<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;
use Siesta\Database\StoredProcedureDefinition;
use Siesta\Driver\MySQL\MySQLStoredProcedureFactory;
use Siesta\Util\ArrayUtil;

class MySQLStoredProcedure implements StoredProcedureDefinition
{

    const SHOW_CREATE_PROCEDURE = "SHOW CREATE PROCEDURE %s;";

    /**
     * @var string
     */
    private string $procedureName;

    /**
     * @var string
     */
    private string $createProcedureStatement;

    /**
     * @var string
     */
    private string $dropProcedureStatement;


    /**
     * MySQLStoredProcedure constructor.
     * @param string $procedureName
     */
    public function __construct(string $procedureName)
    {
        $this->procedureName = $procedureName;
        $this->dropProcedureStatement = sprintf(MySQLStoredProcedureFactory::DROP_PROCEDURE, $procedureName);
    }

    /**
     * @param Connection $connection
     */
    public function load(Connection $connection): void
    {
        $query = sprintf(self::SHOW_CREATE_PROCEDURE, $this->procedureName);
        $resultSet = $connection->query($query);

        while ($resultSet->hasNext()) {
            $this->createProcedureStatement = $resultSet->getStringValue("Create Procedure") . ';';
        }
        $resultSet->close();

        preg_match("/CREATE(.*?) PROCEDURE.*?/", $this->createProcedureStatement, $matches);

        $match = ArrayUtil::getFromArray($matches, 1);
        if ($match) {
            $this->createProcedureStatement = str_replace($match, "", $this->createProcedureStatement);
        }
    }

    /**
     * @return string|null
     */
    public function getDropProcedureStatement(): ?string
    {
        return $this->dropProcedureStatement;
    }

    /**
     * @return string|null
     */
    public function getCreateProcedureStatement(): ?string
    {
        return preg_replace('/\s+/', ' ', $this->createProcedureStatement);
    }

    /**
     * @return string
     */
    public function getProcedureName(): string
    {
        return $this->procedureName;
    }

    /**
     * @param string $createProcedureStatement
     */
    public function setCreateProcedureStatement(string $createProcedureStatement): void
    {
        $this->createProcedureStatement = $createProcedureStatement;
    }

    /**
     * @param string $dropProcedureStatement
     */
    public function setDropProcedureStatement(string $dropProcedureStatement): void
    {
        $this->dropProcedureStatement = $dropProcedureStatement;
    }
}