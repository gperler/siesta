<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Database\StoredProcedureDefinition;
use Siesta\Driver\MySQL\MySQLDriver;
use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\StoredProcedureParameter;

/**
 * @author Gregor Müller
 */
abstract class MySQLStoredProcedureBase implements StoredProcedureDefinition
{

    const CREATE_PROCEDURE = "CREATE PROCEDURE %s %s %s BEGIN %s END;";

    const READS_DATA = "NOT DETERMINISTIC READS SQL DATA SQL SECURITY INVOKER";

    const MODIFIES_DATA = "NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY INVOKER";

    const SELECT_WHERE = "SELECT * FROM %s WHERE %s;";

    const DELETE_WHERE = "DELETE FROM %s WHERE %s;";

    const SP_PARAMETER = "IN %s %s";

    const WHERE = "%s = %s";

    /**
     * @var DataModel
     */
    protected $datamodel;

    /**
     * @var Entity
     */
    protected $entity;

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
    protected $replicationTableName;

    /**
     * @var bool
     */
    protected $isReplication;

    /**
     * MySQLStoredProcedureBase constructor.
     *
     * @param DataModel $dataModel
     * @param Entity $entity
     */
    public function __construct(DataModel $dataModel, Entity $entity)
    {
        $this->entity = $entity;
        $this->isReplication = $entity->getIsReplication();
    }

    /**
     * @return void
     */
    protected function determineTableNames()
    {
        $this->tableName = $this->quote($this->entity->getTableName());
        $this->delimitTable = $this->quote($this->entity->getDelimitTableName());
        $this->replicationTableName = $this->quote($this->entity->getReplicationTableName());
    }

    /**
     * @return string
     */
    public function getCreateProcedureStatement()
    {
        $config = ($this->modifies) ? self::MODIFIES_DATA : self::READS_DATA;

        $name = $this->quote($this->name);

        $definition = sprintf(self::CREATE_PROCEDURE, $name, $this->signature, $config, $this->statement);

        return $definition;
    }

    /**
     * @return string
     */
    public function getDropProcedureStatement()
    {
        return "DROP PROCEDURE IF EXISTS " . $this->quote($this->name);
    }

    /**
     * @param Attribute[] $attributeList
     *
     * @return string
     */
    protected function buildSignatureForAttributeList(array $attributeList) : string
    {
        $parameterPart = [];
        foreach ($attributeList as $attribute) {
            $parameterPart[] = $this->buildSignatureParameterForAttribute($attribute);
        }
        return implode(", ", $parameterPart);
    }

    /**
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function buildSignatureParameterForAttribute(Attribute $attribute) : string
    {
        return $this->buildSignatureParameter($attribute->getStoredProcedureParameterName(), $attribute->getDbType());
    }

    /**
     * @param string $spName
     * @param string $dbType
     *
     * @return string
     */
    protected function buildSignatureParameter(string $spName, string $dbType)
    {
        return sprintf(self::SP_PARAMETER, $spName, $dbType);
    }

    /**
     * @param StoredProcedureParameter[] $parameterList
     *
     * @return string
     */
    protected function buildSignatureFromList(array $parameterList) : string
    {
        return "(" . implode(", ", $parameterList) . ")";
    }

    /**
     * @param string[] $whereList
     *
     * @return string
     */
    public function buildWhereAndSnippet(array $whereList) : string
    {
        return implode(" AND ", $whereList);
    }

    /**
     * @param Attribute $attribute
     *
     * @return string
     */
    public function buildWherePart(Attribute $attribute)
    {
        $name = $this->quote($attribute->getDBName());
        return sprintf(self::WHERE, $name, $attribute->getStoredProcedureParameterName());
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function quote($name)
    {
        return MySQLDriver::quote($name);
    }

}