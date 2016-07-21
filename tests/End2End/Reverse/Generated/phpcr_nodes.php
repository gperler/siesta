<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\StringUtil;

class phpcr_nodes implements ArraySerializable
{

    const TABLE_NAME = "phpcr_nodes";

    const COLUMN_ID = "id";

    const COLUMN_PATH = "path";

    const COLUMN_PARENT = "parent";

    const COLUMN_LOCAL_NAME = "local_name";

    const COLUMN_NAMESPACE = "namespace";

    const COLUMN_WORKSPACE_NAME = "workspace_name";

    const COLUMN_IDENTIFIER = "identifier";

    const COLUMN_TYPE = "type";

    const COLUMN_PROPS = "props";

    const COLUMN_NUMERICAL_PROPS = "numerical_props";

    const COLUMN_DEPTH = "depth";

    const COLUMN_SORT_ORDER = "sort_order";

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
    protected $id;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $parent;

    /**
     * @var string
     */
    protected $local_name;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $workspace_name;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $props;

    /**
     * @var string
     */
    protected $numerical_props;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @var int
     */
    protected $sort_order;

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
        $spCall = ($this->_existing) ? "CALL phpcr_nodes_U(" : "CALL phpcr_nodes_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->path) . ',' . Escaper::quoteString($connection, $this->parent) . ',' . Escaper::quoteString($connection, $this->local_name) . ',' . Escaper::quoteString($connection, $this->namespace) . ',' . Escaper::quoteString($connection, $this->workspace_name) . ',' . Escaper::quoteString($connection, $this->identifier) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->props) . ',' . Escaper::quoteString($connection, $this->numerical_props) . ',' . Escaper::quoteInt($this->depth) . ',' . Escaper::quoteInt($this->sort_order) . ');';
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
        $this->id = $resultSet->getIntegerValue("id");
        $this->path = $resultSet->getStringValue("path");
        $this->parent = $resultSet->getStringValue("parent");
        $this->local_name = $resultSet->getStringValue("local_name");
        $this->namespace = $resultSet->getStringValue("namespace");
        $this->workspace_name = $resultSet->getStringValue("workspace_name");
        $this->identifier = $resultSet->getStringValue("identifier");
        $this->type = $resultSet->getStringValue("type");
        $this->props = $resultSet->getStringValue("props");
        $this->numerical_props = $resultSet->getStringValue("numerical_props");
        $this->depth = $resultSet->getIntegerValue("depth");
        $this->sort_order = $resultSet->getIntegerValue("sort_order");
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
        $id = Escaper::quoteInt($this->id);
        $connection->execute("CALL phpcr_nodes_DB_PK($id)");
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
        $this->setId($arrayAccessor->getIntegerValue("id"));
        $this->setPath($arrayAccessor->getStringValue("path"));
        $this->setParent($arrayAccessor->getStringValue("parent"));
        $this->setLocal_name($arrayAccessor->getStringValue("local_name"));
        $this->setNamespace($arrayAccessor->getStringValue("namespace"));
        $this->setWorkspace_name($arrayAccessor->getStringValue("workspace_name"));
        $this->setIdentifier($arrayAccessor->getStringValue("identifier"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setProps($arrayAccessor->getStringValue("props"));
        $this->setNumerical_props($arrayAccessor->getStringValue("numerical_props"));
        $this->setDepth($arrayAccessor->getIntegerValue("depth"));
        $this->setSort_order($arrayAccessor->getIntegerValue("sort_order"));
        $this->_existing = ($this->id !== null);
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
            "id" => $this->getId(),
            "path" => $this->getPath(),
            "parent" => $this->getParent(),
            "local_name" => $this->getLocal_name(),
            "namespace" => $this->getNamespace(),
            "workspace_name" => $this->getWorkspace_name(),
            "identifier" => $this->getIdentifier(),
            "type" => $this->getType(),
            "props" => $this->getProps(),
            "numerical_props" => $this->getNumerical_props(),
            "depth" => $this->getDepth(),
            "sort_order" => $this->getSort_order()
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
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * 
     * @return void
     */
    public function setPath(string $path = null)
    {
        $this->path = StringUtil::trimToNull($path, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     * 
     * @return void
     */
    public function setParent(string $parent = null)
    {
        $this->parent = StringUtil::trimToNull($parent, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLocal_name()
    {
        return $this->local_name;
    }

    /**
     * @param string $local_name
     * 
     * @return void
     */
    public function setLocal_name(string $local_name = null)
    {
        $this->local_name = StringUtil::trimToNull($local_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * 
     * @return void
     */
    public function setNamespace(string $namespace = null)
    {
        $this->namespace = StringUtil::trimToNull($namespace, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getWorkspace_name()
    {
        return $this->workspace_name;
    }

    /**
     * @param string $workspace_name
     * 
     * @return void
     */
    public function setWorkspace_name(string $workspace_name = null)
    {
        $this->workspace_name = StringUtil::trimToNull($workspace_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * 
     * @return void
     */
    public function setIdentifier(string $identifier = null)
    {
        $this->identifier = StringUtil::trimToNull($identifier, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type = null)
    {
        $this->type = StringUtil::trimToNull($type, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getProps()
    {
        return $this->props;
    }

    /**
     * @param string $props
     * 
     * @return void
     */
    public function setProps(string $props = null)
    {
        $this->props = StringUtil::trimToNull($props, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getNumerical_props()
    {
        return $this->numerical_props;
    }

    /**
     * @param string $numerical_props
     * 
     * @return void
     */
    public function setNumerical_props(string $numerical_props = null)
    {
        $this->numerical_props = StringUtil::trimToNull($numerical_props, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     * 
     * @return void
     */
    public function setDepth(int $depth = null)
    {
        $this->depth = $depth;
    }

    /**
     * 
     * @return int|null
     */
    public function getSort_order()
    {
        return $this->sort_order;
    }

    /**
     * @param int $sort_order
     * 
     * @return void
     */
    public function setSort_order(int $sort_order = null)
    {
        $this->sort_order = $sort_order;
    }

    /**
     * @param phpcr_nodes $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_nodes $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}