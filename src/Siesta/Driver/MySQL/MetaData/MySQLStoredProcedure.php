<?php

declare(strict_types=1);

namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Database\Connection;
use Siesta\Database\StoredProcedureDefinition;
use Siesta\Driver\MySQL\MySQLStoredProcedureFactory;

class MySQLStoredProcedure implements StoredProcedureDefinition
{

    const SHOW_CREATE_PROCEDURE = "SHOW CREATE PROCEDURE %s;";

    /**
     * @var string
     */
    private $procedureName;

    /**
     * @var string
     */
    private $createProcedureStatement;

    /**
     * @var string
     */
    private $dropProcedureStatement;


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
    public function load(Connection $connection)
    {
        $query = sprintf(self::SHOW_CREATE_PROCEDURE, $this->procedureName);
        $resultSet = $connection->query($query);

        while ($resultSet->hasNext()) {
            $this->createProcedureStatement = $resultSet->getStringValue("Create Procedure");
        }

        $resultSet->close();
    }

    /**
     * @return string
     */
    public function getDropProcedureStatement()
    {
        return $this->dropProcedureStatement;
    }

    /**
     * @return string
     */
    public function getCreateProcedureStatement()
    {
        return $this->createProcedureStatement;
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
    public function setCreateProcedureStatement(string $createProcedureStatement)
    {
        $this->createProcedureStatement = $createProcedureStatement;
    }

    /**
     * @param string $dropProcedureStatement
     */
    public function setDropProcedureStatement(string $dropProcedureStatement)
    {
        $this->dropProcedureStatement = $dropProcedureStatement;
    }
}