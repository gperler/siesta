<?php

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLConnection;
use siestaphp\driver\mysqli\replication\Replication;

/**
 * Class MySQLStoredProcedureBase
 * @package siestaphp\driver\mysqli\storedprocedures
 */
abstract class MySQLStoredProcedureBase implements MySQLStoredProcedure
{

    const CREATE_PROCEDURE = "CREATE PROCEDURE";

    const READS_DATA = " NOT DETERMINISTIC READS SQL DATA SQL SECURITY INVOKER ";

    const MODIFIES_DATA = " NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY INVOKER ";

    const SELECT_WHERE = "SELECT * FROM %s WHERE %s;";

    const DELETE_WHERE = "DELETE FROM %s WHERE %s;";

    const SP_PARAMETER = "IN %s %s";

    const WHERE = "%s = %s";

    /**
     * @var EntityGeneratorSource
     */
    protected $entitySource;

    /**
     * @var string
     */
    protected $signature;

    /**
     * @var bool
     */
    protected $modifies;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $statement;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $delimitTable;

    /**
     * @var string
     */
    protected $memoryTableName;

    /**
     * @var bool
     */
    protected $replication;

    /**
     * @param EntityGeneratorSource $source
     * @param $replication
     */
    public function __construct(EntityGeneratorSource $source, $replication)
    {
        $this->entitySource = $source;
        $this->replication = $replication;
    }

    /**
     * @return void
     */
    protected function determineTableNames()
    {
        $this->tableName = $this->quote($this->entitySource->getTable());
        $this->delimitTable = $this->quote($this->entitySource->getDelimitTable());
        $this->memoryTableName = $this->quote(Replication::getReplicationTableName($this->tableName));
    }

    /**
     * @return string
     */
    public function buildCreateProcedureStatement()
    {
        $config = ($this->modifies) ? self::MODIFIES_DATA : self::READS_DATA;

        $definition = self::CREATE_PROCEDURE . " " . $this->quote($this->name) . " " . $this->signature . " " . $config . " BEGIN " . $this->statement . " END;";

        return $definition;
    }

    /**
     * @return string
     */
    public function buildProcedureDropStatement()
    {
        return "DROP PROCEDURE IF EXISTS " . $this->quote($this->name);
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    protected function buildSignatureParameterPart(DatabaseColumn $column)
    {
        return sprintf(self::SP_PARAMETER, $column->getSQLParameterName(), $column->getDatabaseType());
    }

    /**
     * @param string[] $parameterList
     *
     * @return string
     */
    protected function buildSignatureSnippet(array $parameterList)
    {
        return "(" . implode(",", $parameterList) . ")";
    }

    /**
     * @param string[] $whereList
     *
     * @return string
     */
    public function buildWhereSnippet(array $whereList)
    {
        return implode(" AND ", $whereList);
    }

    /**
     * @param DatabaseColumn $column
     *
     * @return string
     */
    public function buildWherePart(DatabaseColumn $column)
    {
        $c = $this->quote($column->getDatabaseName());
        return sprintf(self::WHERE, $c, $column->getSQLParameterName());
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function quote($name)
    {
        return MySQLConnection::quote($name);
    }

}