<?php
declare(strict_types=1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class StoredProcedureParameter
{

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $spName;

    /**
     * @var string|null
     */
    protected ?string $phpType;

    /**
     * @var string|null
     */
    protected ?string $dbType;

    /**
     * StoredProcedureParameter constructor.
     */
    public function __construct()
    {
        $this->name = null;
        $this->spName = null;
        $this->phpType = null;
        $this->dbType = null;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getSpName(): ?string
    {
        return $this->spName;
    }

    /**
     * @param string|null $spName
     */
    public function setSpName(?string $spName): void
    {
        $this->spName = $spName;
    }

    /**
     * @return string|null
     */
    public function getPhpType(): ?string
    {
        return $this->phpType;
    }

    /**
     * @param string|null $phpType
     */
    public function setPhpType(?string $phpType): void
    {
        $this->phpType = $phpType;
    }

    /**
     * @return string|null
     */
    public function getDbType(): ?string
    {
        return $this->dbType;
    }

    /**
     * @return int|null
     */
    public function getDbLength(): ?int
    {
        if ($this->getDbType() === null) {
            return null;
        }
        if (preg_match("/VARCHAR\((.*?)\)/i", $this->getDbType(), $regResult)) {
            return (int)$regResult [1];
        }
        return null;
    }

    /**
     * @param string|null $dbType
     */
    public function setDbType(?string $dbType): void
    {
        $this->dbType = $dbType;
    }

}