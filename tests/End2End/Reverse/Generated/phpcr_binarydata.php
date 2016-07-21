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

class phpcr_binarydata implements ArraySerializable
{

    const TABLE_NAME = "phpcr_binarydata";

    const COLUMN_ID = "id";

    const COLUMN_NODE_ID = "node_id";

    const COLUMN_PROPERTY_NAME = "property_name";

    const COLUMN_WORKSPACE_NAME = "workspace_name";

    const COLUMN_IDX = "idx";

    const COLUMN_DATA = "data";

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
     * @var int
     */
    protected $node_id;

    /**
     * @var string
     */
    protected $property_name;

    /**
     * @var string
     */
    protected $workspace_name;

    /**
     * @var int
     */
    protected $idx;

    /**
     * @var string
     */
    protected $data;

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
        $spCall = ($this->_existing) ? "CALL phpcr_binarydata_U(" : "CALL phpcr_binarydata_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->node_id) . ',' . Escaper::quoteString($connection, $this->property_name) . ',' . Escaper::quoteString($connection, $this->workspace_name) . ',' . Escaper::quoteInt($this->idx) . ',' . Escaper::quoteString($connection, $this->data) . ');';
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
        $this->node_id = $resultSet->getIntegerValue("node_id");
        $this->property_name = $resultSet->getStringValue("property_name");
        $this->workspace_name = $resultSet->getStringValue("workspace_name");
        $this->idx = $resultSet->getIntegerValue("idx");
        $this->data = $resultSet->getStringValue("data");
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
        $connection->execute("CALL phpcr_binarydata_DB_PK($id)");
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
        $this->setNode_id($arrayAccessor->getIntegerValue("node_id"));
        $this->setProperty_name($arrayAccessor->getStringValue("property_name"));
        $this->setWorkspace_name($arrayAccessor->getStringValue("workspace_name"));
        $this->setIdx($arrayAccessor->getIntegerValue("idx"));
        $this->setData($arrayAccessor->getStringValue("data"));
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
            "node_id" => $this->getNode_id(),
            "property_name" => $this->getProperty_name(),
            "workspace_name" => $this->getWorkspace_name(),
            "idx" => $this->getIdx(),
            "data" => $this->getData()
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
     * @return int|null
     */
    public function getNode_id()
    {
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
     * 
     * @return string|null
     */
    public function getProperty_name()
    {
        return $this->property_name;
    }

    /**
     * @param string $property_name
     * 
     * @return void
     */
    public function setProperty_name(string $property_name = null)
    {
        $this->property_name = StringUtil::trimToNull($property_name, 255);
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
     * @return int|null
     */
    public function getIdx()
    {
        return $this->idx;
    }

    /**
     * @param int $idx
     * 
     * @return void
     */
    public function setIdx(int $idx = null)
    {
        $this->idx = $idx;
    }

    /**
     * 
     * @return string|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     * 
     * @return void
     */
    public function setData(string $data = null)
    {
        $this->data = StringUtil::trimToNull($data, null);
    }

    /**
     * @param phpcr_binarydata $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_binarydata $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}