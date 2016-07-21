<?php
declare(strict_types = 1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class StoredProcedure
{

    const RESULT_LIST = "list";

    const RESULT_ENITY = "entity";

    const RESULT_RESULT_SET = "resultSet";

    const RESULT_NONE = "none";

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $modifies;

    /**
     * @var string
     */
    protected $resultType;

    /**
     * @var $statement
     */
    protected $statement;

    /**
     * @var StoredProcedureParameter[]
     */
    protected $parameterList;

    /**
     * StoredProcedure constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->parameterList = [];
    }

    /**
     * @return StoredProcedureParameter
     */
    public function newStoredProcedureParameter() : StoredProcedureParameter
    {
        $parameter = new StoredProcedureParameter();
        $this->parameterList[] = $parameter;
        return $parameter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getDBName()
    {
        return $this->entity->getTableName() . "_" . $this->getName();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function getModifies()
    {
        return $this->modifies;
    }

    /**
     * @param boolean $modifies
     */
    public function setModifies($modifies)
    {
        $this->modifies = $modifies;
    }

    /**
     * @return string
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * @param string $resultType
     */
    public function setResultType($resultType)
    {
        $this->resultType = $resultType;
    }

    /**
     * @return StoredProcedureParameter[]
     */
    public function getParameterList()
    {
        return $this->parameterList;
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param string $statement
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;
    }

    /**
     * @return bool
     */
    public function isEntityResult() : bool
    {
        return $this->resultType === self::RESULT_ENITY;
    }

    /**
     * @return bool
     */
    public function isListResult() : bool
    {
        return $this->resultType === self::RESULT_LIST;
    }

    /**
     * @return bool
     */
    public function isResultSetResult() : bool
    {
        return $this->resultType === self::RESULT_RESULT_SET;
    }

    /**
     * @return bool
     */
    public function isResultTypeNone() : bool
    {
        return $this->resultType === self::RESULT_NONE;
    }
}