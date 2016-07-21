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

class phpcr_type_props implements ArraySerializable
{

    const TABLE_NAME = "phpcr_type_props";

    const COLUMN_NODE_TYPE_ID = "node_type_id";

    const COLUMN_NAME = "name";

    const COLUMN_PROTECTED = "protected";

    const COLUMN_AUTO_CREATED = "auto_created";

    const COLUMN_MANDATORY = "mandatory";

    const COLUMN_ON_PARENT_VERSION = "on_parent_version";

    const COLUMN_MULTIPLE = "multiple";

    const COLUMN_FULLTEXT_SEARCHABLE = "fulltext_searchable";

    const COLUMN_QUERY_ORDERABLE = "query_orderable";

    const COLUMN_REQUIRED_TYPE = "required_type";

    const COLUMN_QUERY_OPERATORS = "query_operators";

    const COLUMN_DEFAULT_VALUE = "default_value";

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
    protected $node_type_id;

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
    protected $auto_created;

    /**
     * @var string
     */
    protected $mandatory;

    /**
     * @var int
     */
    protected $on_parent_version;

    /**
     * @var string
     */
    protected $multiple;

    /**
     * @var string
     */
    protected $fulltext_searchable;

    /**
     * @var string
     */
    protected $query_orderable;

    /**
     * @var int
     */
    protected $required_type;

    /**
     * @var int
     */
    protected $query_operators;

    /**
     * @var string
     */
    protected $default_value;

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
        $this->getNode_type_id(true, $connectionName);
        $this->getName(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->node_type_id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->protected) . ',' . Escaper::quoteString($connection, $this->auto_created) . ',' . Escaper::quoteString($connection, $this->mandatory) . ',' . Escaper::quoteInt($this->on_parent_version) . ',' . Escaper::quoteString($connection, $this->multiple) . ',' . Escaper::quoteString($connection, $this->fulltext_searchable) . ',' . Escaper::quoteString($connection, $this->query_orderable) . ',' . Escaper::quoteInt($this->required_type) . ',' . Escaper::quoteInt($this->query_operators) . ',' . Escaper::quoteString($connection, $this->default_value) . ');';
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
        $this->node_type_id = $resultSet->getIntegerValue("node_type_id");
        $this->name = $resultSet->getStringValue("name");
        $this->protected = $resultSet->getStringValue("protected");
        $this->auto_created = $resultSet->getStringValue("auto_created");
        $this->mandatory = $resultSet->getStringValue("mandatory");
        $this->on_parent_version = $resultSet->getIntegerValue("on_parent_version");
        $this->multiple = $resultSet->getStringValue("multiple");
        $this->fulltext_searchable = $resultSet->getStringValue("fulltext_searchable");
        $this->query_orderable = $resultSet->getStringValue("query_orderable");
        $this->required_type = $resultSet->getIntegerValue("required_type");
        $this->query_operators = $resultSet->getIntegerValue("query_operators");
        $this->default_value = $resultSet->getStringValue("default_value");
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
        $node_type_id = Escaper::quoteInt($this->node_type_id);
        $name = Escaper::quoteString($connection, $this->name);
        $connection->execute("CALL phpcr_type_props_DB_PK($node_type_id,$name)");
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
        $this->setNode_type_id($arrayAccessor->getIntegerValue("node_type_id"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setProtected($arrayAccessor->getStringValue("protected"));
        $this->setAuto_created($arrayAccessor->getStringValue("auto_created"));
        $this->setMandatory($arrayAccessor->getStringValue("mandatory"));
        $this->setOn_parent_version($arrayAccessor->getIntegerValue("on_parent_version"));
        $this->setMultiple($arrayAccessor->getStringValue("multiple"));
        $this->setFulltext_searchable($arrayAccessor->getStringValue("fulltext_searchable"));
        $this->setQuery_orderable($arrayAccessor->getStringValue("query_orderable"));
        $this->setRequired_type($arrayAccessor->getIntegerValue("required_type"));
        $this->setQuery_operators($arrayAccessor->getIntegerValue("query_operators"));
        $this->setDefault_value($arrayAccessor->getStringValue("default_value"));
        $this->_existing = ($this->node_type_id !== null) && ($this->name !== null);
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
            "node_type_id" => $this->getNode_type_id(),
            "name" => $this->getName(),
            "protected" => $this->getProtected(),
            "auto_created" => $this->getAuto_created(),
            "mandatory" => $this->getMandatory(),
            "on_parent_version" => $this->getOn_parent_version(),
            "multiple" => $this->getMultiple(),
            "fulltext_searchable" => $this->getFulltext_searchable(),
            "query_orderable" => $this->getQuery_orderable(),
            "required_type" => $this->getRequired_type(),
            "query_operators" => $this->getQuery_operators(),
            "default_value" => $this->getDefault_value()
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
    public function getNode_type_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->node_type_id === null) {
            $this->node_type_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->node_type_id;
    }

    /**
     * @param int $node_type_id
     * 
     * @return void
     */
    public function setNode_type_id(int $node_type_id = null)
    {
        $this->node_type_id = $node_type_id;
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
    public function getAuto_created()
    {
        return $this->auto_created;
    }

    /**
     * @param string $auto_created
     * 
     * @return void
     */
    public function setAuto_created(string $auto_created = null)
    {
        $this->auto_created = StringUtil::trimToNull($auto_created, null);
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
    public function getOn_parent_version()
    {
        return $this->on_parent_version;
    }

    /**
     * @param int $on_parent_version
     * 
     * @return void
     */
    public function setOn_parent_version(int $on_parent_version = null)
    {
        $this->on_parent_version = $on_parent_version;
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
    public function getFulltext_searchable()
    {
        return $this->fulltext_searchable;
    }

    /**
     * @param string $fulltext_searchable
     * 
     * @return void
     */
    public function setFulltext_searchable(string $fulltext_searchable = null)
    {
        $this->fulltext_searchable = StringUtil::trimToNull($fulltext_searchable, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getQuery_orderable()
    {
        return $this->query_orderable;
    }

    /**
     * @param string $query_orderable
     * 
     * @return void
     */
    public function setQuery_orderable(string $query_orderable = null)
    {
        $this->query_orderable = StringUtil::trimToNull($query_orderable, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getRequired_type()
    {
        return $this->required_type;
    }

    /**
     * @param int $required_type
     * 
     * @return void
     */
    public function setRequired_type(int $required_type = null)
    {
        $this->required_type = $required_type;
    }

    /**
     * 
     * @return int|null
     */
    public function getQuery_operators()
    {
        return $this->query_operators;
    }

    /**
     * @param int $query_operators
     * 
     * @return void
     */
    public function setQuery_operators(int $query_operators = null)
    {
        $this->query_operators = $query_operators;
    }

    /**
     * 
     * @return string|null
     */
    public function getDefault_value()
    {
        return $this->default_value;
    }

    /**
     * @param string $default_value
     * 
     * @return void
     */
    public function setDefault_value(string $default_value = null)
    {
        $this->default_value = StringUtil::trimToNull($default_value, 255);
    }

    /**
     * @param phpcr_type_props $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_type_props $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getNode_type_id() === $entity->getNode_type_id() && $this->getName() === $entity->getName();
    }

}