<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Driver\MySQL\MySQLDriver;
use Siesta\Model\DelimitAttributeList;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class InsertStatement
{

    const INSERT_STATEMENT_WITH_UPDATE = "INSERT INTO %s ( %s ) VALUES ( %s ) ON DUPLICATE KEY UPDATE %s;";
    const INSERT_STATEMENT_WITHOUT_UPDATE = "INSERT INTO %s ( %s ) VALUES ( %s );";

    const SP_PARAMETER = "IN %s %s";

    const SET = "%s = %s";


    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string[]
     */
    protected array $columnList;

    /**
     * @var string[]
     */
    protected array $valueList;

    /**
     * @var string[]
     */
    protected array $parameterList;

    /**
     * @var array
     */
    protected array $updateList;

    /**
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->columnList = [];
        $this->valueList = [];
        $this->parameterList = [];
        $this->updateList = [];
        $this->extractColumnAndValueList();
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    public function buildInsert(string $tableName): string
    {
        return $this->buildStatement(
            $tableName,
            $this->columnList,
            $this->valueList,
            $this->updateList,
        );
    }

    /**
     * @return string
     */
    public function buildSignature(): string
    {
        return "(" . implode(", ", $this->parameterList) . ")";
    }

    /**
     * @return string
     */
    public function buildDelimitInsert(): string
    {
        $columnList = array_merge($this->columnList);
        $valueList = array_merge($this->valueList);
        $updateList = array_merge($this->updateList);

        foreach (DelimitAttributeList::getDelimitAttributes($this->entity) as $delimitAttribute) {
            $attributeName = $delimitAttribute->getDBName();

            $columnName = MySQLDriver::quote($attributeName);

            $columnList[] = $columnName;

            if ($attributeName === DelimitAttributeList::COLUMN_DELIMIT_ID) {
                $valueList[] = 'UUID()';
                $updateList[] = sprintf(
                    self::SET,
                    $columnName,
                    'UUID()'
                );

            }
            if ($attributeName === DelimitAttributeList::COLUMN_VALID_FROM) {
                $valueList[] = 'NOW()';
                $updateList[] = sprintf(
                    self::SET,
                    $columnName,
                    'NOW()'
                );
            }
            if ($attributeName === DelimitAttributeList::COLUMN_VALID_UNTIL) {
                $valueList[] = 'NULL';
                $updateList[] = sprintf(
                    self::SET,
                    $columnName,
                    'NULL'
                );
            }

        }

        return $this->buildStatement(
            $this->entity->getDelimitTableName(),
            $columnList,
            $valueList,
            $updateList
        );
    }

    /**
     * @param string $tableName
     * @param string[] $columnList
     * @param string[] $valueList
     * @param $updateList
     * @return string
     */
    protected function buildStatement(string $tableName, array $columnList, array $valueList, $updateList): string
    {
        return sprintf(
            $this->getStatement(),
            $tableName,
            implode(", ", $columnList),
            implode(", ", $valueList),
            implode(", ", $updateList)
        );
    }

    /**
     * @return string
     */
    private function getStatement(): string
    {
        if (count($this->updateList) === 0) {
            return self::INSERT_STATEMENT_WITHOUT_UPDATE;
        }
        return self::INSERT_STATEMENT_WITH_UPDATE;
    }

    /**
     * @return void
     */
    private function extractColumnAndValueList(): void
    {

        foreach ($this->entity->getAttributeList() as $attribute) {
            if ($attribute->getIsTransient()) {
                continue;
            }
            $columnName = MySQLDriver::quote($attribute->getDBName());

            $this->columnList[] = $columnName;
            $this->valueList[] = $attribute->getStoredProcedureParameterName();

            if (!$attribute->getIsPrimaryKey()) {
                $this->updateList[] = sprintf(
                    self::SET,
                    $columnName,
                    $attribute->getStoredProcedureParameterName()
                );
            }

            $this->parameterList[] = sprintf(self::SP_PARAMETER, $attribute->getStoredProcedureParameterName(), $attribute->getDbType());
        }
    }

}