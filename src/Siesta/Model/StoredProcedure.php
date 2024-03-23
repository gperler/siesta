<?php
declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class StoredProcedure
{

    const RESULT_LIST = "list";

    const RESULT_ENTITY = "entity";

    const RESULT_RESULT_SET = "resultSet";

    const RESULT_NONE = "none";

    const ALLOWED_RESULT = [
        self::RESULT_ENTITY,
        self::RESULT_LIST,
        self::RESULT_RESULT_SET,
        self::RESULT_NONE
    ];

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var bool
     */
    protected bool $modifies;

    /**
     * @var string|null
     */
    protected ?string $resultType;

    /**
     * @var string|null $statement
     */
    protected ?string $statement;

    /**
     * @var StoredProcedureParameter[]
     */
    protected array $parameterList;

    /**
     * StoredProcedure constructor.
     *
     * @param Entity $entity
     */
    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
        $this->name = null;
        $this->modifies = false;
        $this->resultType = null;
        $this->statement = null;
        $this->parameterList = [];
    }

    /**
     * @return StoredProcedureParameter
     */
    public function newStoredProcedureParameter(): StoredProcedureParameter
    {
        $parameter = new StoredProcedureParameter();
        $this->parameterList[] = $parameter;
        return $parameter;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDBName(): string
    {
        return $this->entity->getTableName() . "_" . $this->getName();
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function getModifies(): bool
    {
        return $this->modifies;
    }

    /**
     * @param bool $modifies
     */
    public function setModifies(bool $modifies): void
    {
        $this->modifies = $modifies;
    }

    /**
     * @return string|null
     */
    public function getResultType(): ?string
    {
        return $this->resultType;
    }

    /**
     * @param string|null $resultType
     */
    public function setResultType(?string $resultType): void
    {
        $this->resultType = $resultType;
    }

    /**
     * @return StoredProcedureParameter[]
     */
    public function getParameterList(): array
    {
        return $this->parameterList;
    }

    /**
     * @return string|null
     */
    public function getStatement(): ?string
    {
        return $this->statement;
    }

    /**
     * @param string|null $statement
     */
    public function setStatement(?string $statement): void
    {
        $this->statement = $statement;
    }

    /**
     * @return bool
     */
    public function isEntityResult(): bool
    {
        return $this->resultType === self::RESULT_ENTITY;
    }

    /**
     * @return bool
     */
    public function isListResult(): bool
    {
        return $this->resultType === self::RESULT_LIST;
    }

    /**
     * @return bool
     */
    public function isResultSetResult(): bool
    {
        return $this->resultType === self::RESULT_RESULT_SET;
    }

    /**
     * @return bool
     */
    public function isResultTypeNone(): bool
    {
        return $this->resultType === self::RESULT_NONE;
    }
}