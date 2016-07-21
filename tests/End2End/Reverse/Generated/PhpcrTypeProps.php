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

class PhpcrTypeProps implements ArraySerializable
{

    const TABLE_NAME = "phpcr_type_props";

    const COLUMN_NODETYPEID = "node_type_id";

    const COLUMN_NAME = "name";

    const COLUMN_PROTECTED = "protected";

    const COLUMN_AUTOCREATED = "auto_created";

    const COLUMN_MANDATORY = "mandatory";

    const COLUMN_ONPARENTVERSION = "on_parent_version";

    const COLUMN_MULTIPLE = "multiple";

    const COLUMN_FULLTEXTSEARCHABLE = "fulltext_searchable";

    const COLUMN_QUERYORDERABLE = "query_orderable";

    const COLUMN_REQUIREDTYPE = "required_type";

    const COLUMN_QUERYOPERATORS = "query_operators";

    const COLUMN_DEFAULTVALUE = "default_value";

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
    protected $multiple;

    /**
     * @var string
     */
    protected $fulltextSearchable;

    /**
     * @var string
     */
    protected $queryOrderable;

    /**
     * @var int
     */
    protected $requiredType;

    /**
     * @var int
     */
    protected $queryOperators;

    /**
     * @var string
     */
    protected $defaultValue;

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
        $spCall = ($this->_existing) ? "CALL phpcr_type_props_U(" : "CALL phpcr_type_props_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getNodeTypeId(true, $connectionName);
        $this->getName(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->nodeTypeId) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->protected) . ',' . Escaper::quoteString($connection, $this->autoCreated) . ',' . Escaper::quoteString($connection, $this->mandatory) . ',' . Escaper::quoteInt($this->onParentVersion) . ',' . Escaper::quoteString($connection, $this->multiple) . ',' . Escaper::quoteString($connection, $this->fulltextSearchable) . ',' . Escaper::quoteString($connection, $this->queryOrderable) . ',' . Escaper::quoteInt($this->requiredType) . ',' . Escaper::quoteInt($this->queryOperators) . ',' . Escaper::quoteString($connection, $this->defaultValue) . ');';
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
        $this->multiple = $resultSet->getStringValue("multiple");
        $this->fulltextSearchable = $resultSet->getStringValue("fulltext_searchable");
        $this->queryOrderable = $resultSet->getStringValue("query_orderable");
        $this->requiredType = $resultSet->getIntegerValue("required_type");
        $this->queryOperators = $resultSet->getIntegerValue("query_operators");
        $this->defaultValue = $resultSet->getStringValue("default_value");
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
        $nodeTypeId = Escaper::quoteInt($this->nodeTypeId);
        $name = Escaper::quoteString($connection, $this->name);
        $connection->execute("CALL phpcr_type_props_DB_PK($nodeTypeId,$name)");
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
        $this->setMultiple($arrayAccessor->getStringValue("multiple"));
        $this->setFulltextSearchable($arrayAccessor->getStringValue("fulltextSearchable"));
        $this->setQueryOrderable($arrayAccessor->getStringValue("queryOrderable"));
        $this->setRequiredType($arrayAccessor->getIntegerValue("requiredType"));
        $this->setQueryOperators($arrayAccessor->getIntegerValue("queryOperators"));
        $this->setDefaultValue($arrayAccessor->getStringValue("defaultValue"));
        $this->_existing = ($this->nodeTypeId !== null) && ($this->name !== null);
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
            "multiple" => $this->getMultiple(),
            "fulltextSearchable" => $this->getFulltextSearchable(),
            "queryOrderable" => $this->getQueryOrderable(),
            "requiredType" => $this->getRequiredType(),
            "queryOperators" => $this->getQueryOperators(),
            "defaultValue" => $this->getDefaultValue()
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
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getNodeTypeId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->nodeTypeId === null) {
            $this->nodeTypeId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return string|null
     */
    public function getName(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->name === null) {
            $this->name = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param string $multiple
     * 
     * @return void
     */
    public function setMultiple(string $multiple = null)
    {
        $this->multiple = StringUtil::trimToNull($multiple, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getFulltextSearchable()
    {
        return $this->fulltextSearchable;
    }

    /**
     * @param string $fulltextSearchable
     * 
     * @return void
     */
    public function setFulltextSearchable(string $fulltextSearchable = null)
    {
        $this->fulltextSearchable = StringUtil::trimToNull($fulltextSearchable, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getQueryOrderable()
    {
        return $this->queryOrderable;
    }

    /**
     * @param string $queryOrderable
     * 
     * @return void
     */
    public function setQueryOrderable(string $queryOrderable = null)
    {
        $this->queryOrderable = StringUtil::trimToNull($queryOrderable, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getRequiredType()
    {
        return $this->requiredType;
    }

    /**
     * @param int $requiredType
     * 
     * @return void
     */
    public function setRequiredType(int $requiredType = null)
    {
        $this->requiredType = $requiredType;
    }

    /**
     * 
     * @return int|null
     */
    public function getQueryOperators()
    {
        return $this->queryOperators;
    }

    /**
     * @param int $queryOperators
     * 
     * @return void
     */
    public function setQueryOperators(int $queryOperators = null)
    {
        $this->queryOperators = $queryOperators;
    }

    /**
     * 
     * @return string|null
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     * 
     * @return void
     */
    public function setDefaultValue(string $defaultValue = null)
    {
        $this->defaultValue = StringUtil::trimToNull($defaultValue, 255);
    }

    /**
     * @param PhpcrTypeProps $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrTypeProps $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getNodeTypeId() === $entity->getNodeTypeId() && $this->getName() === $entity->getName();
    }

}