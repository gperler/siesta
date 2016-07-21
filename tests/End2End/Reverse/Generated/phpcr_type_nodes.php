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

class phpcr_type_nodes implements ArraySerializable
{

    const TABLE_NAME = "phpcr_type_nodes";

    const COLUMN_NODE_TYPE_ID = "node_type_id";

    const COLUMN_NAME = "name";

    const COLUMN_SUPERTYPES = "supertypes";

    const COLUMN_IS_ABSTRACT = "is_abstract";

    const COLUMN_IS_MIXIN = "is_mixin";

    const COLUMN_QUERYABLE = "queryable";

    const COLUMN_ORDERABLE_CHILD_NODES = "orderable_child_nodes";

    const COLUMN_PRIMARY_ITEM = "primary_item";

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
    protected $supertypes;

    /**
     * @var string
     */
    protected $is_abstract;

    /**
     * @var string
     */
    protected $is_mixin;

    /**
     * @var string
     */
    protected $queryable;

    /**
     * @var string
     */
    protected $orderable_child_nodes;

    /**
     * @var string
     */
    protected $primary_item;

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
        $spCall = ($this->_existing) ? "CALL phpcr_type_nodes_U(" : "CALL phpcr_type_nodes_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getNode_type_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->node_type_id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->supertypes) . ',' . Escaper::quoteString($connection, $this->is_abstract) . ',' . Escaper::quoteString($connection, $this->is_mixin) . ',' . Escaper::quoteString($connection, $this->queryable) . ',' . Escaper::quoteString($connection, $this->orderable_child_nodes) . ',' . Escaper::quoteString($connection, $this->primary_item) . ');';
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
        $this->supertypes = $resultSet->getStringValue("supertypes");
        $this->is_abstract = $resultSet->getStringValue("is_abstract");
        $this->is_mixin = $resultSet->getStringValue("is_mixin");
        $this->queryable = $resultSet->getStringValue("queryable");
        $this->orderable_child_nodes = $resultSet->getStringValue("orderable_child_nodes");
        $this->primary_item = $resultSet->getStringValue("primary_item");
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
        $connection->execute("CALL phpcr_type_nodes_DB_PK($node_type_id)");
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
        $this->setSupertypes($arrayAccessor->getStringValue("supertypes"));
        $this->setIs_abstract($arrayAccessor->getStringValue("is_abstract"));
        $this->setIs_mixin($arrayAccessor->getStringValue("is_mixin"));
        $this->setQueryable($arrayAccessor->getStringValue("queryable"));
        $this->setOrderable_child_nodes($arrayAccessor->getStringValue("orderable_child_nodes"));
        $this->setPrimary_item($arrayAccessor->getStringValue("primary_item"));
        $this->_existing = ($this->node_type_id !== null);
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
            "supertypes" => $this->getSupertypes(),
            "is_abstract" => $this->getIs_abstract(),
            "is_mixin" => $this->getIs_mixin(),
            "queryable" => $this->getQueryable(),
            "orderable_child_nodes" => $this->getOrderable_child_nodes(),
            "primary_item" => $this->getPrimary_item()
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
            $this->node_type_id = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
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
    public function getSupertypes()
    {
        return $this->supertypes;
    }

    /**
     * @param string $supertypes
     * 
     * @return void
     */
    public function setSupertypes(string $supertypes = null)
    {
        $this->supertypes = StringUtil::trimToNull($supertypes, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getIs_abstract()
    {
        return $this->is_abstract;
    }

    /**
     * @param string $is_abstract
     * 
     * @return void
     */
    public function setIs_abstract(string $is_abstract = null)
    {
        $this->is_abstract = StringUtil::trimToNull($is_abstract, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getIs_mixin()
    {
        return $this->is_mixin;
    }

    /**
     * @param string $is_mixin
     * 
     * @return void
     */
    public function setIs_mixin(string $is_mixin = null)
    {
        $this->is_mixin = StringUtil::trimToNull($is_mixin, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getQueryable()
    {
        return $this->queryable;
    }

    /**
     * @param string $queryable
     * 
     * @return void
     */
    public function setQueryable(string $queryable = null)
    {
        $this->queryable = StringUtil::trimToNull($queryable, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getOrderable_child_nodes()
    {
        return $this->orderable_child_nodes;
    }

    /**
     * @param string $orderable_child_nodes
     * 
     * @return void
     */
    public function setOrderable_child_nodes(string $orderable_child_nodes = null)
    {
        $this->orderable_child_nodes = StringUtil::trimToNull($orderable_child_nodes, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getPrimary_item()
    {
        return $this->primary_item;
    }

    /**
     * @param string $primary_item
     * 
     * @return void
     */
    public function setPrimary_item(string $primary_item = null)
    {
        $this->primary_item = StringUtil::trimToNull($primary_item, 255);
    }

    /**
     * @param phpcr_type_nodes $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_type_nodes $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getNode_type_id() === $entity->getNode_type_id();
    }

}