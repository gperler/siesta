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

class phpcr_namespaces implements ArraySerializable
{

    const TABLE_NAME = "phpcr_namespaces";

    const COLUMN_PREFIX = "prefix";

    const COLUMN_URI = "uri";

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
    protected $prefix;

    /**
     * @var string
     */
    protected $uri;

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
        $spCall = ($this->_existing) ? "CALL phpcr_namespaces_U(" : "CALL phpcr_namespaces_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getPrefix(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->prefix) . ',' . Escaper::quoteString($connection, $this->uri) . ');';
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
        $this->prefix = $resultSet->getStringValue("prefix");
        $this->uri = $resultSet->getStringValue("uri");
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
        $prefix = Escaper::quoteString($connection, $this->prefix);
        $connection->execute("CALL phpcr_namespaces_DB_PK($prefix)");
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
        $this->setPrefix($arrayAccessor->getStringValue("prefix"));
        $this->setUri($arrayAccessor->getStringValue("uri"));
        $this->_existing = ($this->prefix !== null);
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
            "prefix" => $this->getPrefix(),
            "uri" => $this->getUri()
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
    public function getPrefix(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->prefix === null) {
            $this->prefix = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * 
     * @return void
     */
    public function setPrefix(string $prefix = null)
    {
        $this->prefix = StringUtil::trimToNull($prefix, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * 
     * @return void
     */
    public function setUri(string $uri = null)
    {
        $this->uri = StringUtil::trimToNull($uri, 255);
    }

    /**
     * @param phpcr_namespaces $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(phpcr_namespaces $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getPrefix() === $entity->getPrefix();
    }

}