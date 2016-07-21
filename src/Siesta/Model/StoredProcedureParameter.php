<?php
declare(strict_types = 1);

namespace Siesta\Model;

/**
 * @author Gregor Müller
 */
class StoredProcedureParameter
{

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
     * StoredProcedureParameter constructor.
     */
    public function __construct()
    {

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