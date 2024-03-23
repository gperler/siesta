<?php
declare(strict_types=1);

namespace Siesta\XML;

/**
 * @author Gregor Müller
 */
class XMLStoredProcedure
{

    const ELEMENT_SP_NAME = "storedProcedure";

    const ELEMENT_SQL_NAME = "sql";

    const NAME = "name";

    const MODIFIES = "modifies";

    const DETERMINISTIC = "deterministic";

    const RESULT_TYPE = "resultType";

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var bool
     */
    protected bool $modifies;

    /**
     * @var bool
     */
    protected bool $deterministic;

    /**
     * @var string|null
     */
    protected ?string $resultType;

    /**
     * @var string|null
     */
    protected ?string $statement;

    /**
     * @var XMLStoredProcedureParameter[]
     */
    protected array $xmlParameterList;


    /**
     * XMLStoredProcedure constructor.
     */
    public function __construct()
    {
        $this->name = null;
        $this->modifies = false;
        $this->deterministic = false;
        $this->resultType = null;
        $this->statement = null;
        $this->xmlParameterList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess): void
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setModifies($xmlAccess->getAttributeAsBool(self::MODIFIES));
        $this->setDeterministic($xmlAccess->getAttributeAsBool(self::DETERMINISTIC));
        $this->setResultType($xmlAccess->getAttribute(self::RESULT_TYPE));
        $this->setStatement($xmlAccess->getFirstChildByNameContent(self::ELEMENT_SQL_NAME));


        foreach ($xmlAccess->getXMLChildElementListByName(XMLStoredProcedureParameter::ELEMENT_PARAMETER_NAME) as $xmlParameterAccess) {
            $xmlParameter = new XMLStoredProcedureParameter();
            $xmlParameter->fromXML($xmlParameterAccess);
            $this->xmlParameterList[] = $xmlParameter;
        }
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
     * @return bool
     */
    public function getDeterministic(): bool
    {
        return $this->deterministic;
    }

    /**
     * @param bool $deterministic
     */
    public function setDeterministic(bool $deterministic): void
    {
        $this->deterministic = $deterministic;
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
     * @return XMLStoredProcedureParameter[]
     */
    public function getXmlParameterList(): array
    {
        return $this->xmlParameterList;
    }


}