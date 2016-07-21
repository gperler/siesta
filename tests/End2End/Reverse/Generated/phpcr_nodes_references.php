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

class phpcr_nodes_references implements ArraySerializable
{

    const TABLE_NAME = "phpcr_nodes_references";

    const COLUMN_SOURCE_ID = "source_id";

    const COLUMN_SOURCE_PROPERTY_NAME = "source_property_name";

    const COLUMN_TARGET_ID = "target_id";

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
    protected $source_id;

    /**
     * @var string
     */
    protected $source_property_name;

    /**
     * @var int
     */
    protected $target_id;

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
        $spCall = ($this->_existing) ? "CALL phpcr_nodes_references_U(" : "CALL phpcr_nodes_references_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getSource_id(true, $connectionName);
        $this->getSource_property_name(true, $connectionName);
        $this->getTarget_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->source_id) . ',' . Escaper::quoteString($connection, $this->source_property_name) . ',' . Escaper::quoteInt($this->target_id) . ');';
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
        $this->source_id = $resultSet->getIntegerValue("source_id");
        $this->source_property_name = $resultSet->getStringValue("source_property_name");
        $this->target_id = $resultSet->getIntegerValue("target_id");
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
        $source_id = Escaper::quoteInt($this->source_id);
        $source_property_name = Escaper::quoteString($connection, $this->source_property_name);
        $target_id = Escaper::quoteInt($this->target_id);
        $connection->execute("CALL phpcr_nodes_references_DB_PK($source_id,$source_property_name,$target_id)");
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
        $this->setSource_id($arrayAccessor->getIntegerValue("source_id"));
        $this->setSource_property_name($arrayAccessor->getStringValue("source_property_name"));
        $this->setTarget_id($arrayAccessor->getIntegerValue("target_id"));
        $this->_existing = ($this->source_id !== null) && ($this->source_property_name !== null) && ($this->target_id !== null);
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
            "source_id" => $this->getSource_id(),
            "source_property_name" => $this->getSource_property_name(),
            "target_id" => $this->getTarget_id()
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
    public function getSource_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->source_id === null) {
            $this->source_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->source_id;
    }

    /**
     * @param int $source_id
     * 
     * @return void
     */
    public function setSource_id(int $source_id = null)
    {
        $this->source_id = $source_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return string|null
     */
    public function getSource_property_name(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->source_property_name === null) {
            $this->source_property_name = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->source_property_name;
    }

    /**
     * @param string $source_property_name
     * 
     * @return void
     */
    public function setSource_property_name(string $source_property_name = null)
    {
        $this->source_property_name = StringUtil::trimToNull($source_property_name, 220);
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getTarget_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->target_id === null) {
            $this->target_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->target_id;
    }

    /**
     * @param int $target_id
     * 
     * @return void
     */
    public function setTarget_id(int $target_id = null)
    {
        $this->target_id = $target_id;
    }

    /**
     * @param phpcr_nodes_references $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_nodes_references $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getSource_id() === $entity->getSource_id() && $this->getSource_property_name() === $entity->getSource_property_name() && $this->getTarget_id() === $entity->getTarget_id();
    }

}