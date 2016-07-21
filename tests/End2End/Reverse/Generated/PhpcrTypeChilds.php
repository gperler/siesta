<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\StringUtil;

class PhpcrTypeChilds implements ArraySerializable
{

    const TABLE_NAME = "phpcr_type_childs";

    const COLUMN_NODETYPEID = "node_type_id";

    const COLUMN_NAME = "name";

    const COLUMN_PROTECTED = "protected";

    const COLUMN_AUTOCREATED = "auto_created";

    const COLUMN_MANDATORY = "mandatory";

    const COLUMN_ONPARENTVERSION = "on_parent_version";

    const COLUMN_PRIMARYTYPES = "primary_types";

    const COLUMN_DEFAULTTYPE = "default_type";

    /**
     * @var bool
     */
    protected $_existing;

    /**
     * @var array
     */
    protected $_rawJSON;

    /**
     * @var array
     */
    protected $_rawSQLResult;

    /**
     * @var int
     */
    protected $nodeTypeId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $protected;

    /**
     * @var string
     */
    protected $autoCreated;

    /**
     * @var string
     */
    protected $mandatory;

    /**
     * @var int
     */
    protected $onParentVersion;

    /**
     * @var string
     */
    protected $primaryTypes;

    /**
     * @var string
     */
    protected $defaultType;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL phpcr_type_childs_U(" : "CALL phpcr_type_childs_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        return $spCall . Escaper::quoteInt($this->nodeTypeId) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->protected) . ',' . Escaper::quoteString($connection, $this->autoCreated) . ',' . Escaper::quoteString($connection, $this->mandatory) . ',' . Escaper::quoteInt($this->onParentVersion) . ',' . Escaper::quoteString($connection, $this->primaryTypes) . ',' . Escaper::quoteString($connection, $this->defaultType) . ');';
    }

    /**
     * @param bool $cascade
     * @param CycleDetector $cycleDetector
     * @param string $connectionName
     * 
     * @return void
     */
    public function save(bool $cascade = false, CycleDetector $cycleDetector = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return;
        }
        $call = $this->createSaveStoredProcedureCall($connectionName);
        $connection->execute($call);
        $this->_existing = true;
        if (!$cascade) {
            return;
        }
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return void
     */
    public function fromResultSet(ResultSet $resultSet)
    {
        $this->_existing = true;
        $this->_rawSQLResult = $resultSet->getNext();
        $this->nodeTypeId = $resultSet->getIntegerValue("node_type_id");
        $this->name = $resultSet->getStringValue("name");
        $this->protected = $resultSet->getStringValue("protected");
        $this->autoCreated = $resultSet->getStringValue("auto_created");
        $this->mandatory = $resultSet->getStringValue("mandatory");
        $this->onParentVersion = $resultSet->getIntegerValue("on_parent_version");
        $this->primaryTypes = $resultSet->getStringValue("primary_types");
        $this->defaultType = $resultSet->getStringValue("default_type");
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function getAdditionalColumn(string $key)
    {
        return ArrayUtil::getFromArray($this->_rawSQLResult, $key);
    }

    /**
     * @param string $connectionName
     * 
     * @return void
     */
    public function delete(string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $connection->execute("CALL phpcr_type_childs_DB_PK()");
        $this->_existing = false;
    }

    /**
     * @param array $data
     * 
     * @return void
     */
    public function fromArray(array $data)
    {
        $this->_rawJSON = $data;
        $arrayAccessor = new ArrayAccessor($data);
        $this->setNodeTypeId($arrayAccessor->getIntegerValue("nodeTypeId"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setProtected($arrayAccessor->getStringValue("protected"));
        $this->setAutoCreated($arrayAccessor->getStringValue("autoCreated"));
        $this->setMandatory($arrayAccessor->getStringValue("mandatory"));
        $this->setOnParentVersion($arrayAccessor->getIntegerValue("onParentVersion"));
        $this->setPrimaryTypes($arrayAccessor->getStringValue("primaryTypes"));
        $this->setDefaultType($arrayAccessor->getStringValue("defaultType"));
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return array|null
     */
    public function toArray(CycleDetector $cycleDetector = null)
    {
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return null;
        }
        $result = [
            "nodeTypeId" => $this->getNodeTypeId(),
            "name" => $this->getName(),
            "protected" => $this->getProtected(),
            "autoCreated" => $this->getAutoCreated(),
            "mandatory" => $this->getMandatory(),
            "onParentVersion" => $this->getOnParentVersion(),
            "primaryTypes" => $this->getPrimaryTypes(),
            "defaultType" => $this->getDefaultType()
        ];
        return $result;
    }

    /**
     * @param string $jsonString
     * 
     * @return void
     */
    public function fromJSON(string $jsonString)
    {
        $this->fromArray(json_decode($jsonString, true));
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return string
     */
    public function toJSON(CycleDetector $cycleDetector = null) : string
    {
        return json_encode($this->toArray($cycleDetector));
    }

    /**
     * 
     * @return int|null
     */
    public function getNodeTypeId()
    {
        return $this->nodeTypeId;
    }

    /**
     * @param int $nodeTypeId
     * 
     * @return void
     */
    public function setNodeTypeId(int $nodeTypeId = null)
    {
        $this->nodeTypeId = $nodeTypeId;
    }

    /**
     * 
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name = null)
    {
        $this->name = StringUtil::trimToNull($name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * @param string $protected
     * 
     * @return void
     */
    public function setProtected(string $protected = null)
    {
        $this->protected = StringUtil::trimToNull($protected, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getAutoCreated()
    {
        return $this->autoCreated;
    }

    /**
     * @param string $autoCreated
     * 
     * @return void
     */
    public function setAutoCreated(string $autoCreated = null)
    {
        $this->autoCreated = StringUtil::trimToNull($autoCreated, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * @param string $mandatory
     * 
     * @return void
     */
    public function setMandatory(string $mandatory = null)
    {
        $this->mandatory = StringUtil::trimToNull($mandatory, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getOnParentVersion()
    {
        return $this->onParentVersion;
    }

    /**
     * @param int $onParentVersion
     * 
     * @return void
     */
    public function setOnParentVersion(int $onParentVersion = null)
    {
        $this->onParentVersion = $onParentVersion;
    }

    /**
     * 
     * @return string|null
     */
    public function getPrimaryTypes()
    {
        return $this->primaryTypes;
    }

    /**
     * @param string $primaryTypes
     * 
     * @return void
     */
    public function setPrimaryTypes(string $primaryTypes = null)
    {
        $this->primaryTypes = StringUtil::trimToNull($primaryTypes, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getDefaultType()
    {
        return $this->defaultType;
    }

    /**
     * @param string $defaultType
     * 
     * @return void
     */
    public function setDefaultType(string $defaultType = null)
    {
        $this->defaultType = StringUtil::trimToNull($defaultType, 255);
    }

    /**
     * @param PhpcrTypeChilds $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrTypeChilds $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return false;
    }

}