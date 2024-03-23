<?php
declare(strict_types=1);

namespace Siesta\XML;

/**
 * @author Gregor Müller
 */
class XMLStoredProcedureParameter
{

    const ELEMENT_PARAMETER_NAME = "parameter";

    const NAME = "name";

    const SP_NAME = "spName";

    const PHP_TYPE = "type";

    const DB_TYPE = "dbType";

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
     * XMLStoredProcedureParameter constructor.
     */
    public function __construct()
    {
        $this->name = null;
        $this->spName = null;
        $this->phpType = null;
        $this->dbType = null;
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setSpName($xmlAccess->getAttribute(self::SP_NAME));
        $this->setPhpType($xmlAccess->getAttribute(self::PHP_TYPE));
        $this->setDbType($xmlAccess->getAttribute(self::DB_TYPE));
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
     * @param string|null $dbType
     */
    public function setDbType(?string $dbType): void
    {
        $this->dbType = $dbType;
    }


}