<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL\StoredProcedure;

use Siesta\Driver\MySQL\MySQLDriver;
use Siesta\Model\DelimitAttributeList;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class UpdateStatement
{
    const SP_PARAMETER = "IN %s %s";

    const UPDATE = "UPDATE %s SET %s WHERE %s;";

    const DELIMTER_UPDATE = "UPDATE %s SET `%s` = NOW() WHERE %s AND `%s` IS NULL;";

    const WHERE = "%s = %s";

    const SET = "%s = %s";

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string[]
     */
    protected array $setList;

    /**
     * @var string[]
     */
    protected array $parameterList;

    /**
     * @var string[]
     */
    protected array $whereList;

    /**
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->setList = [];
        $this->whereList = [];
        $this->parameterList = [];
        $this->extractColumnAndValueList();
    }

    /**
     * @return string
     */
    public function buildSignature(): string
    {
        return "(" . implode(",", $this->parameterList) . ")";
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    public function buildUpdate(string $tableName): string
    {
        $setList = implode(",", $this->setList);
        $where = implode(" AND ", $this->whereList);
        return sprintf(self::UPDATE, $tableName, $setList, $where);
    }

    /**
     * @return string
     */
    public function buildDelimitUpdate(): string
    {
        $tableName = MySQLDriver::quote($this->entity->getDelimitTableName());
        $where = implode(" AND ", $this->whereList);
        $validUntilName = DelimitAttributeList::COLUMN_VALID_UNTIL;
        return sprintf(self::DELIMTER_UPDATE, $tableName, $validUntilName, $where, $validUntilName);
    }

    /**
     * @return void
     */
    private function extractColumnAndValueList(): void
    {

        // iterate attributes
        foreach ($this->entity->getAttributeList() as $attribute) {

            // skip transient attributes
            if ($attribute->getIsTransient()) {
                continue;
            }

            $columnName = MySQLDriver::quote($attribute->getDBName());
            $this->setList[] = sprintf(self::SET, $columnName, $attribute->getStoredProcedureParameterName());
            $this->parameterList[] = sprintf(self::SP_PARAMETER, $attribute->getStoredProcedureParameterName(), $attribute->getDbType());
        }

        // build where statement
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $columnName = MySQLDriver::quote($attribute->getDBName());
            $spParameterName = $attribute->getStoredProcedureParameterName();
            $this->whereList[] = sprintf(self::WHERE, $columnName, $spParameterName);
        }
    }

}