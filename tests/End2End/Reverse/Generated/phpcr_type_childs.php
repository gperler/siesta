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

class phpcr_type_childs implements ArraySerializable
{

    const TABLE_NAME = "phpcr_type_childs";

    const COLUMN_NODE_TYPE_ID = "node_type_id";

    const COLUMN_NAME = "name";

    const COLUMN_PROTECTED = "protected";

    const COLUMN_AUTO_CREATED = "auto_created";

    const COLUMN_MANDATORY = "mandatory";

    const COLUMN_ON_PARENT_VERSION = "on_parent_version";

    const COLUMN_PRIMARY_TYPES = "primary_types";

    const COLUMN_DEFAULT_TYPE = "default_type";

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
    protected $primary_types;

    /**
     * @var string
     */
    protected $default_type;

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
        return $spCall . Escaper::quoteInt($this->node_type_id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->protected) . ',' . Escaper::quoteString($connection, $this->auto_created) . ',' . Escaper::quoteString($connection, $this->mandatory) . ',' . Escaper::quoteInt($this->on_parent_version) . ',' . Escaper::quoteString($connection, $this->primary_types) . ',' . Escaper::quoteString($connection, $this->default_type) . ');';
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
        $this->primary_types = $resultSet->getStringValue("primary_types");
        $this->default_type = $resultSet->getStringValue("default_type");
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
        $this->setNode_type_id($arrayAccessor->getIntegerValue("node_type_id"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setProtected($arrayAccessor->getStringValue("protected"));
        $this->setAuto_created($arrayAccessor->getStringValue("auto_created"));
        $this->setMandatory($arrayAccessor->getStringValue("mandatory"));
        $this->setOn_parent_version($arrayAccessor->getIntegerValue("on_parent_version"));
        $this->setPrimary_types($arrayAccessor->getStringValue("primary_types"));
        $this->setDefault_type($arrayAccessor->getStringValue("default_type"));
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
            "primary_types" => $this->getPrimary_types(),
            "default_type" => $this->getDefault_type()
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
    public function getNode_type_id()
    {
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
    public function getPrimary_types()
    {
        return $this->primary_types;
    }

    /**
     * @param string $primary_types
     * 
     * @return void
     */
    public function setPrimary_types(string $primary_types = null)
    {
        $this->primary_types = StringUtil::trimToNull($primary_types, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getDefault_type()
    {
        return $this->default_type;
    }

    /**
     * @param string $default_type
     * 
     * @return void
     */
    public function setDefault_type(string $default_type = null)
    {
        $this->default_type = StringUtil::trimToNull($default_type, 255);
    }

    /**
     * @param phpcr_type_childs $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_type_childs $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return false;
    }

}