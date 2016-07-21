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

class PhpcrBinarydata implements ArraySerializable
{

    const TABLE_NAME = "phpcr_binarydata";

    const COLUMN_ID = "id";

    const COLUMN_NODEID = "node_id";

    const COLUMN_PROPERTYNAME = "property_name";

    const COLUMN_WORKSPACENAME = "workspace_name";

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
    protected $nodeId;

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var string
     */
    protected $workspaceName;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->nodeId) . ',' . Escaper::quoteString($connection, $this->propertyName) . ',' . Escaper::quoteString($connection, $this->workspaceName) . ',' . Escaper::quoteInt($this->idx) . ',' . Escaper::quoteString($connection, $this->data) . ');';
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
        $this->nodeId = $resultSet->getIntegerValue("node_id");
        $this->propertyName = $resultSet->getStringValue("property_name");
        $this->workspaceName = $resultSet->getStringValue("workspace_name");
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
        $this->setNodeId($arrayAccessor->getIntegerValue("nodeId"));
        $this->setPropertyName($arrayAccessor->getStringValue("propertyName"));
        $this->setWorkspaceName($arrayAccessor->getStringValue("workspaceName"));
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
            "nodeId" => $this->getNodeId(),
            "propertyName" => $this->getPropertyName(),
            "workspaceName" => $this->getWorkspaceName(),
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
    public function getNodeId()
    {
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
     * 
     * @return string|null
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName
     * 
     * @return void
     */
    public function setPropertyName(string $propertyName = null)
    {
        $this->propertyName = StringUtil::trimToNull($propertyName, 255);
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
     * @param PhpcrBinarydata $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrBinarydata $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}