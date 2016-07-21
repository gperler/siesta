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

class PhpcrNodesWeakreferences implements ArraySerializable
{

    const TABLE_NAME = "phpcr_nodes_weakreferences";

    const COLUMN_SOURCEID = "source_id";

    const COLUMN_SOURCEPROPERTYNAME = "source_property_name";

    const COLUMN_TARGETID = "target_id";

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
    protected $sourceId;

    /**
     * @var string
     */
    protected $sourcePropertyName;

    /**
     * @var int
     */
    protected $targetId;

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
        $spCall = ($this->_existing) ? "CALL phpcr_nodes_weakreferences_U(" : "CALL phpcr_nodes_weakreferences_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getSourceId(true, $connectionName);
        $this->getSourcePropertyName(true, $connectionName);
        $this->getTargetId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->sourceId) . ',' . Escaper::quoteString($connection, $this->sourcePropertyName) . ',' . Escaper::quoteInt($this->targetId) . ');';
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
        $this->sourceId = $resultSet->getIntegerValue("source_id");
        $this->sourcePropertyName = $resultSet->getStringValue("source_property_name");
        $this->targetId = $resultSet->getIntegerValue("target_id");
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
        $sourceId = Escaper::quoteInt($this->sourceId);
        $sourcePropertyName = Escaper::quoteString($connection, $this->sourcePropertyName);
        $targetId = Escaper::quoteInt($this->targetId);
        $connection->execute("CALL phpcr_nodes_weakreferences_DB_PK($sourceId,$sourcePropertyName,$targetId)");
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
        $this->setSourceId($arrayAccessor->getIntegerValue("sourceId"));
        $this->setSourcePropertyName($arrayAccessor->getStringValue("sourcePropertyName"));
        $this->setTargetId($arrayAccessor->getIntegerValue("targetId"));
        $this->_existing = ($this->sourceId !== null) && ($this->sourcePropertyName !== null) && ($this->targetId !== null);
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
            "sourceId" => $this->getSourceId(),
            "sourcePropertyName" => $this->getSourcePropertyName(),
            "targetId" => $this->getTargetId()
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
    public function getSourceId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->sourceId === null) {
            $this->sourceId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     * 
     * @return void
     */
    public function setSourceId(int $sourceId = null)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return string|null
     */
    public function getSourcePropertyName(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->sourcePropertyName === null) {
            $this->sourcePropertyName = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->sourcePropertyName;
    }

    /**
     * @param string $sourcePropertyName
     * 
     * @return void
     */
    public function setSourcePropertyName(string $sourcePropertyName = null)
    {
        $this->sourcePropertyName = StringUtil::trimToNull($sourcePropertyName, 220);
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getTargetId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->targetId === null) {
            $this->targetId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->targetId;
    }

    /**
     * @param int $targetId
     * 
     * @return void
     */
    public function setTargetId(int $targetId = null)
    {
        $this->targetId = $targetId;
    }

    /**
     * @param PhpcrNodesWeakreferences $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(PhpcrNodesWeakreferences $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getSourceId() === $entity->getSourceId() && $this->getSourcePropertyName() === $entity->getSourcePropertyName() && $this->getTargetId() === $entity->getTargetId();
    }

}