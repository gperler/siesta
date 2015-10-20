<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 20.10.15
 * Time: 22:56
 */

namespace siestaphp\driver\mysqli\storedprocedures;

use siestaphp\datamodel\delimit\DelimitAttribute;
use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\driver\mysqli\MySQLDriver;

/**
 * Class InsertStatement
 * @package siestaphp\driver\mysqli\storedprocedures
 */
class InsertStatement
{

    const INSERT_STATEMENT = "INSERT INTO %s ( %s ) VALUES ( %s );";

    const SP_PARAMETER = "IN %s %s";

    /**
     * @var EntityGeneratorSource
     */
    protected $entity;

    /**
     * @var string[]
     */
    protected $columnList;

    /**
     * @var string[]
     */
    protected $valueList;

    /**
     * @var string[]
     */
    protected $parameterList;

    /**
     * @param EntityGeneratorSource $entity
     */
    public function __construct(EntityGeneratorSource $entity)
    {
        $this->entity = $entity;
        $this->columnList = array();
        $this->valueList = array();
        $this->parameterList = array();
        $this->extractColumAndValueList();
    }

    /**
     * @return string
     */
    public function buildInsert()
    {
        return $this->buildStatement($this->entity->getTable(), $this->columnList, $this->valueList);
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
    public function buildDelimitInsert()
    {
        $columnList = array_merge($this->columnList);
        $valueList = array_merge($this->valueList);

        foreach (DelimitAttribute::getDelimitAttributes() as $delimitAttribute) {
            $columnList[] = MySQLDriver::quote($delimitAttribute->getDatabaseName());
            $valueList[] = $delimitAttribute->getInsertDefault();
        }

        return $this->buildStatement($this->entity->getDelimitTable(), $columnList, $valueList);
    }

    /**
     * @param string $tableName
     * @param string[] $columnList
     * @param string[] $valueList
     *
     * @return string
     */
    protected function buildStatement($tableName, array $columnList, array $valueList)
    {
        $tableName = MySQLDriver::quote($tableName);
        $columnSQL = implode(",", $columnList);
        $valueSQL = implode(",", $valueList);
        return sprintf(self::INSERT_STATEMENT, $tableName, $columnSQL, $valueSQL);
    }

    /**
     * @return void
     */
    private function extractColumAndValueList()
    {
        // iterate referenced columns first
        foreach ($this->entity->getReferenceGeneratorSourceList() as $reference) {
            foreach ($reference->getReferencedColumnList() as $column) {
                $this->columnList[] = MySQLDriver::quote($column->getDatabaseName());
                $this->valueList[] = $column->getSQLParameterName();
                $this->parameterList[] = sprintf(self::SP_PARAMETER, $column->getSQLParameterName(), $column->getDatabaseType());
            }
        }

        // iterate attributes
        foreach ($this->entity->getAttributeGeneratorSourceList() as $attribute) {
            if ($attribute->isTransient()) {
                continue;
            }
            $this->columnList[] = MySQLDriver::quote($attribute->getDatabaseName());
            $this->valueList[] = $attribute->getSQLParameterName();
            $this->parameterList[] = sprintf(self::SP_PARAMETER, $attribute->getSQLParameterName(), $attribute->getDatabaseType());
        }
    }

}