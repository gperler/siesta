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

class PhpcrInternalIndexTypes implements ArraySerializable
{

    const TABLE_NAME = "phpcr_internal_index_types";

    const COLUMN_TYPE = "type";

    const COLUMN_NODEID = "node_id";

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
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $nodeId;

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
        $spCall = ($this->_existing) ? "CALL phpcr_internal_index_types_U(" : "CALL phpcr_internal_index_types_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getType(true, $connectionName);
        $this->getNodeId(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteInt($this->nodeId) . ');';
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
        $this->type = $resultSet->getStringValue("type");
        $this->nodeId = $resultSet->getIntegerValue("node_id");
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
        $type = Escaper::quoteString($connection, $this->type);
        $nodeId = Escaper::quoteInt($this->nodeId);
        $connection->execute("CALL phpcr_internal_index_types_DB_PK($type,$nodeId)");
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
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->setNodeId($arrayAccessor->getIntegerValue("nodeId"));
        $this->_existing = ($this->type !== null) && ($this->nodeId !== null);
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
            "type" => $this->getType(),
            "nodeId" => $this->getNodeId()
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
     * @return string|null
     */
    public function getType(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->type === null) {
            $this->type = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getNodeId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->nodeId === null) {
            $this->nodeId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->nodeId;
    }

    /**
     * @param int $nodeId
     * 
     * @return void
     */
    public function setNodeId(int $nodeId = null)
    {
        $this->nodeId = $nodeId;
    }

    /**
     * @param PhpcrInternalIndexTypes $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrInternalIndexTypes $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getType() === $entity->getType() && $this->getNodeId() === $entity->getNodeId();
    }

}