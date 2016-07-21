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

class phpcr_internal_index_types implements ArraySerializable
{

    const TABLE_NAME = "phpcr_internal_index_types";

    const COLUMN_TYPE = "type";

    const COLUMN_NODE_ID = "node_id";

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
    protected $node_id;

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
        $this->getNode_id(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->type) . ',' . Escaper::quoteInt($this->node_id) . ');';
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
        $this->node_id = $resultSet->getIntegerValue("node_id");
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
        $node_id = Escaper::quoteInt($this->node_id);
        $connection->execute("CALL phpcr_internal_index_types_DB_PK($type,$node_id)");
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
        $this->setNode_id($arrayAccessor->getIntegerValue("node_id"));
        $this->_existing = ($this->type !== null) && ($this->node_id !== null);
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
            "node_id" => $this->getNode_id()
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
    public function getNode_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->node_id === null) {
            $this->node_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->node_id;
    }

    /**
     * @param int $node_id
     * 
     * @return void
     */
    public function setNode_id(int $node_id = null)
    {
        $this->node_id = $node_id;
    }

    /**
     * @param phpcr_internal_index_types $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_internal_index_types $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getType() === $entity->getType() && $this->getNode_id() === $entity->getNode_id();
    }

}