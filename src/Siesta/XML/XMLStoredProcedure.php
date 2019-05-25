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
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $modifies;

    /**
     * @var bool
     */
    protected $deterministic;

    /**
     * @var string
     */
    protected $resultType;

    /**
     * @var string
     */
    protected $statement;

    /**
     * @var XMLStoredProcedureParameter[]
     */
    protected $xmlParameterList;


    /**
     * XMLStoredProcedure constructor.
     */
    public function __construct()
    {
        $this->xmlParameterList = [];
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setModifies($xmlAccess->getAttributeAsBool(self::MODIFIES));
        $this->setDeterministic($xmlAccess->getAttributeAsBool(self::DETERMINISTIC));
        $this->setResultType($xmlAccess->getAttribute(self::RESULT_TYPE));
        $this->setStatement($xmlAccess->getFirstChildByNameContent(self::ELEMENT_SQL_NAME));


        foreach ($xmlAccess->getXMLChildElementListByName(XMLStoredProcedureParameter::ELEMENT_PARAMETER_NAME) as $key => $xmlParameterAccess) {
            $xmlParameter = new XMLStoredProcedureParameter();
            $xmlParameter->fromXML($xmlParameterAccess);
            $this->xmlParameterList[] = $xmlParameter;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function getModifies()
    {
        return $this->modifies;
    }

    /**
     * @param bool $modifies
     */
    public function setModifies($modifies)
    {
        $this->modifies = $modifies;
    }

    /**
     * @return bool
     */
    public function isDeterministic(): bool
    {
        return $this->deterministic;
    }

    /**
     * @param bool $deterministic
     */
    public function setDeterministic(bool $deterministic)
    {
        $this->deterministic = $deterministic;
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
     * @return XMLStoredProcedureParameter[]
     */
    public function getXmlParameterList()
    {
        return $this->xmlParameterList;
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

}