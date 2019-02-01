<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Driver\MySQL\MySQLDriver;
use Siesta\Model\DelimitAttributeList;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class InsertStatement
{

    const INSERT_STATEMENT = "INSERT INTO %s ( %s ) VALUES ( %s );";

    const SP_PARAMETER = "IN %s %s";

    /**
     * @var Entity
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
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->columnList = [];
        $this->valueList = [];
        $this->parameterList = [];
        $this->extractColumnAndValueList();
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    public function buildInsert($tableName)
    {
        return $this->buildStatement($tableName, $this->columnList, $this->valueList);
    }

    /**
     * @return string
     */
    public function buildSignature()
    {
        return "(" . implode(", ", $this->parameterList) . ")";
    }

    /**
     * @return string
     */
    public function buildDelimitInsert()
    {
        $columnList = array_merge($this->columnList);
        $valueList = array_merge($this->valueList);

        foreach (DelimitAttributeList::getDelimitAttributes($this->entity) as $delimitAttribute) {
            $attributeName = $delimitAttribute->getDBName();

            $columnList[] = MySQLDriver::quote($attributeName);

            if ($attributeName === DelimitAttributeList::COLUMN_DELIMIT_ID) {
                $valueList[] = 'UUID()';
            }
            if ($attributeName === DelimitAttributeList::COLUMN_VALID_FROM) {
                $valueList[] = 'NOW()';
            }
            if ($attributeName === DelimitAttributeList::COLUMN_VALID_UNTIL) {
                $valueList[] = 'NULL';
            }

        }

        return $this->buildStatement($this->entity->getDelimitTableName(), $columnList, $valueList);
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
        $columnSQL = implode(", ", $columnList);
        $valueSQL = implode(", ", $valueList);
        return sprintf(self::INSERT_STATEMENT, $tableName, $columnSQL, $valueSQL);
    }

    /**
     * @return void
     */
    private function extractColumnAndValueList()
    {

        foreach ($this->entity->getAttributeList() as $attribute) {
            if ($attribute->getIsTransient()) {
                continue;
            }
            $this->columnList[] = MySQLDriver::quote($attribute->getDBName());
            $this->valueList[] = $attribute->getStoredProcedureParameterName();
            $this->parameterList[] = sprintf(self::SP_PARAMETER, $attribute->getStoredProcedureParameterName(), $attribute->getDbType());
        }
    }

}