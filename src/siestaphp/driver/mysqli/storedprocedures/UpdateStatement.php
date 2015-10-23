<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 20.10.15
 * Time: 23:28
 */

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLDriver;

/**
 * Class UpdateStatement
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class UpdateStatement
{
    const SP_PARAMETER = "IN %s %s";

    const UPDATE = "UPDATE %s SET %s WHERE %s;";

    const DELIMTER_UPDATE = "UPDATE %s SET `_validUntil` = NOW() WHERE %s AND `_validUntil` IS NULL;";

    const WHERE = "%s = %s";

    const SET = "%s = %s";

    /**
     * @var EntityGeneratorSource
     */
    protected $entity;

    /**
     * @var string[]
     */
    protected $setList;

    /**
     * @var string[]
     */
    protected $parameterList;

    /**
     * @var string[]
     */
    protected $whereList;

    /**
     * @param EntityGeneratorSource $entity
     */
    public function __construct(EntityGeneratorSource $entity)
    {
        $this->entity = $entity;
        $this->setList = array();
        $this->whereList = array();
        $this->parameterList = array();
        $this->extractColumAndValueList();
    }

    /**
     * @return string
     */
    public function buildSignature()
    {
        return "(" . implode(",", $this->parameterList) . ")";
    }

    /**
     * @return string
     */
    public function buildUpdate()
    {
        $tableName = MySQLDriver::quote($this->entity->getTable());
        $setList = implode(",", $this->setList);
        $where = implode(" AND ", $this->whereList);
        return sprintf(self::UPDATE, $tableName, $setList, $where);
    }

    /**
     * @return string
     */
    public function buildDelimitUpdate() {
        $tableName = MySQLDriver::quote($this->entity->getDelimitTable());
        $where = implode(" AND ", $this->whereList);
        return sprintf(self::DELIMTER_UPDATE, $tableName, $where);
    }

    /**
     * @return void
     */
    private function extractColumAndValueList()
    {
        // iterate referenced columns first
        foreach ($this->entity->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                if (!$reference->isPrimaryKey()) {

                }
                $columnName = MySQLDriver::quote($column->getDatabaseName());
                $this->setList[] = sprintf(self::SET, $columnName, $column->getSQLParameterName());
                $this->parameterList[] = sprintf(self::SP_PARAMETER, $column->getSQLParameterName(), $column->getDatabaseType());
            }
        }

        // iterate attributes
        foreach ($this->entity->getAttributeGeneratorSourceList() as $attribute) {

            // skip transient attributes
            if ($attribute->isTransient()) {
                continue;
            }

            // create set only for non primary keys
            if (!$attribute->isPrimaryKey()) {

            }
            $columnName = MySQLDriver::quote($attribute->getDatabaseName());
            $this->setList[] = sprintf(self::SET, $columnName, $attribute->getSQLParameterName());
            $this->parameterList[] = sprintf(self::SP_PARAMETER, $attribute->getSQLParameterName(), $attribute->getDatabaseType());
        }

        // build where statement
        foreach ($this->entity->getPrimaryKeyColumns() as $column) {
            $columnName = MySQLDriver::quote($column->getDatabaseName());
            $this->whereList[] = sprintf(self::WHERE, $columnName, $column->getSQLParameterName());
        }
    }

}