<?php
declare(strict_types = 1);

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
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $spName;

    /**
     * @var string
     */
    protected $phpType;

    /**
     * @var string
     */
    protected $dbType;

    /**
     * XMLStoredProcedureParameter constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param XMLAccess $xmlAccess
     */
    public function fromXML(XMLAccess $xmlAccess)
    {
        $this->setName($xmlAccess->getAttribute(self::NAME));
        $this->setSpName($xmlAccess->getAttribute(self::SP_NAME));
        $this->setPhpType($xmlAccess->getAttribute(self::PHP_TYPE));
        $this->setDbType($xmlAccess->getAttribute(self::DB_TYPE));
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
     * @return string
     */
    public function getSpName()
    {
        return $this->spName;
    }

    /**
     * @param string $spName
     */
    public function setSpName($spName)
    {
        $this->spName = $spName;
    }

    /**
     * @return string
     */
    public function getPhpType()
    {
        return $this->phpType;
    }

    /**
     * @param string $phpType
     */
    public function setPhpType($phpType)
    {
        $this->phpType = $phpType;
    }

    /**
     * @return string
     */
    public function getDbType()
    {
        return $this->dbType;
    }

    /**
     * @param string $dbType
     */
    public function setDbType($dbType)
    {
        $this->dbType = $dbType;
    }

}