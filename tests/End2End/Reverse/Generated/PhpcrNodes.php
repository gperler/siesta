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

class PhpcrNodes implements ArraySerializable
{

    const TABLE_NAME = "phpcr_nodes";

    const COLUMN_ID = "id";

    const COLUMN_PATH = "path";

    const COLUMN_PARENT = "parent";

    const COLUMN_LOCALNAME = "local_name";

    const COLUMN_NAMESPACE = "namespace";

    const COLUMN_WORKSPACENAME = "workspace_name";

    const COLUMN_IDENTIFIER = "identifier";

    const COLUMN_TYPE = "type";

    const COLUMN_PROPS = "props";

    const COLUMN_NUMERICALPROPS = "numerical_props";

    const COLUMN_DEPTH = "depth";

    const COLUMN_SORTORDER = "sort_order";

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
    protected $localName;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $workspaceName;

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
    protected $numericalProps;

    /**
     * @var int
     */
    protected $depth;

    /**
     * @var int
     */
    protected $sortOrder;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->path) . ',' . Escaper::quoteString($connection, $this->parent) . ',' . Escaper::quoteString($connection, $this->localName) . ',' . Escaper::quoteString($connection, $this->namespace) . ',' . Escaper::quoteString($connection, $this->workspaceName) . ',' . Escaper::quoteString($connection, $this->identifier) . ',' . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteString($connection, $this->props) . ',' . Escaper::quoteString($connection, $this->numericalProps) . ',' . Escaper::quoteInt($this->depth) . ',' . Escaper::quoteInt($this->sortOrder) . ');';
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
        $this->localName = $resultSet->getStringValue("local_name");
        $this->namespace = $resultSet->getStringValue("namespace");
        $this->workspaceName = $resultSet->getStringValue("workspace_name");
        $this->identifier = $resultSet->getStringValue("identifier");
        $this->type = $resultSet->getStringValue("type");
        $this->props = $resultSet->getStringValue("props");
        $this->numericalProps = $resultSet->getStringValue("numerical_props");
        $this->depth = $resultSet->getIntegerValue("depth");
        $this->sortOrder = $resultSet->getIntegerValue("sort_order");
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
        $this->setLocalName($arrayAccessor->getStringValue("localName"));
        $this->setNamespace($arrayAccessor->getStringValue("namespace"));
        $this->setWorkspaceName($arrayAccessor->getStringValue("workspaceName"));
        $this->setIdentifier($arrayAccessor->getStringValue("identifier"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setProps($arrayAccessor->getStringValue("props"));
        $this->setNumericalProps($arrayAccessor->getStringValue("numericalProps"));
        $this->setDepth($arrayAccessor->getIntegerValue("depth"));
        $this->setSortOrder($arrayAccessor->getIntegerValue("sortOrder"));
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
            "localName" => $this->getLocalName(),
            "namespace" => $this->getNamespace(),
            "workspaceName" => $this->getWorkspaceName(),
            "identifier" => $this->getIdentifier(),
            "type" => $this->getType(),
            "props" => $this->getProps(),
            "numericalProps" => $this->getNumericalProps(),
            "depth" => $this->getDepth(),
            "sortOrder" => $this->getSortOrder()
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
    public function getLocalName()
    {
        return $this->localName;
    }

    /**
     * @param string $localName
     * 
     * @return void
     */
    public function setLocalName(string $localName = null)
    {
        $this->localName = StringUtil::trimToNull($localName, 255);
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
    public function getWorkspaceName()
    {
        return $this->workspaceName;
    }

    /**
     * @param string $workspaceName
     * 
     * @return void
     */
    public function setWorkspaceName(string $workspaceName = null)
    {
        $this->workspaceName = StringUtil::trimToNull($workspaceName, 255);
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
    public function getNumericalProps()
    {
        return $this->numericalProps;
    }

    /**
     * @param string $numericalProps
     * 
     * @return void
     */
    public function setNumericalProps(string $numericalProps = null)
    {
        $this->numericalProps = StringUtil::trimToNull($numericalProps, null);
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
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     * 
     * @return void
     */
    public function setSortOrder(int $sortOrder = null)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @param PhpcrNodes $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrNodes $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}