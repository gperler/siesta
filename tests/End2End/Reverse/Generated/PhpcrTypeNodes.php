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

class PhpcrTypeNodes implements ArraySerializable
{

    const TABLE_NAME = "phpcr_type_nodes";

    const COLUMN_NODETYPEID = "node_type_id";

    const COLUMN_NAME = "name";

    const COLUMN_SUPERTYPES = "supertypes";

    const COLUMN_ISABSTRACT = "is_abstract";

    const COLUMN_ISMIXIN = "is_mixin";

    const COLUMN_QUERYABLE = "queryable";

    const COLUMN_ORDERABLECHILDNODES = "orderable_child_nodes";

    const COLUMN_PRIMARYITEM = "primary_item";

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
    protected $supertypes;

    /**
     * @var string
     */
    protected $isAbstract;

    /**
     * @var string
     */
    protected $isMixin;

    /**
     * @var string
     */
    protected $queryable;

    /**
     * @var string
     */
    protected $orderableChildNodes;

    /**
     * @var string
     */
    protected $primaryItem;

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
        $this->getNodeTypeId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->nodeTypeId) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->supertypes) . ',' . Escaper::quoteString($connection, $this->isAbstract) . ',' . Escaper::quoteString($connection, $this->isMixin) . ',' . Escaper::quoteString($connection, $this->queryable) . ',' . Escaper::quoteString($connection, $this->orderableChildNodes) . ',' . Escaper::quoteString($connection, $this->primaryItem) . ');';
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
        $this->supertypes = $resultSet->getStringValue("supertypes");
        $this->isAbstract = $resultSet->getStringValue("is_abstract");
        $this->isMixin = $resultSet->getStringValue("is_mixin");
        $this->queryable = $resultSet->getStringValue("queryable");
        $this->orderableChildNodes = $resultSet->getStringValue("orderable_child_nodes");
        $this->primaryItem = $resultSet->getStringValue("primary_item");
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
        $connection->execute("CALL phpcr_type_nodes_DB_PK($nodeTypeId)");
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
        $this->setSupertypes($arrayAccessor->getStringValue("supertypes"));
        $this->setIsAbstract($arrayAccessor->getStringValue("isAbstract"));
        $this->setIsMixin($arrayAccessor->getStringValue("isMixin"));
        $this->setQueryable($arrayAccessor->getStringValue("queryable"));
        $this->setOrderableChildNodes($arrayAccessor->getStringValue("orderableChildNodes"));
        $this->setPrimaryItem($arrayAccessor->getStringValue("primaryItem"));
        $this->_existing = ($this->nodeTypeId !== null);
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
            "supertypes" => $this->getSupertypes(),
            "isAbstract" => $this->getIsAbstract(),
            "isMixin" => $this->getIsMixin(),
            "queryable" => $this->getQueryable(),
            "orderableChildNodes" => $this->getOrderableChildNodes(),
            "primaryItem" => $this->getPrimaryItem()
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
            $this->nodeTypeId = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
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
    public function getIsAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * @param string $isAbstract
     * 
     * @return void
     */
    public function setIsAbstract(string $isAbstract = null)
    {
        $this->isAbstract = StringUtil::trimToNull($isAbstract, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getIsMixin()
    {
        return $this->isMixin;
    }

    /**
     * @param string $isMixin
     * 
     * @return void
     */
    public function setIsMixin(string $isMixin = null)
    {
        $this->isMixin = StringUtil::trimToNull($isMixin, null);
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
    public function getOrderableChildNodes()
    {
        return $this->orderableChildNodes;
    }

    /**
     * @param string $orderableChildNodes
     * 
     * @return void
     */
    public function setOrderableChildNodes(string $orderableChildNodes = null)
    {
        $this->orderableChildNodes = StringUtil::trimToNull($orderableChildNodes, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getPrimaryItem()
    {
        return $this->primaryItem;
    }

    /**
     * @param string $primaryItem
     * 
     * @return void
     */
    public function setPrimaryItem(string $primaryItem = null)
    {
        $this->primaryItem = StringUtil::trimToNull($primaryItem, 255);
    }

    /**
     * @param PhpcrTypeNodes $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrTypeNodes $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getNodeTypeId() === $entity->getNodeTypeId();
    }

}