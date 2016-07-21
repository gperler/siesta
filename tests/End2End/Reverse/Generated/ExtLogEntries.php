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
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class ExtLogEntries implements ArraySerializable
{

    const TABLE_NAME = "ext_log_entries";

    const COLUMN_ID = "id";

    const COLUMN_ACTION = "action";

    const COLUMN_LOGGEDAT = "logged_at";

    const COLUMN_OBJECTID = "object_id";

    const COLUMN_OBJECTCLASS = "object_class";

    const COLUMN_VERSION = "version";

    const COLUMN_DATA = "data";

    const COLUMN_USERNAME = "username";

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
    protected $action;

    /**
     * @var SiestaDateTime
     */
    protected $loggedAt;

    /**
     * @var string
     */
    protected $objectId;

    /**
     * @var string
     */
    protected $objectClass;

    /**
     * @var int
     */
    protected $version;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $username;

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
        $spCall = ($this->_existing) ? "CALL ext_log_entries_U(" : "CALL ext_log_entries_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->action) . ',' . Escaper::quoteDateTime($this->loggedAt) . ',' . Escaper::quoteString($connection, $this->objectId) . ',' . Escaper::quoteString($connection, $this->objectClass) . ',' . Escaper::quoteInt($this->version) . ',' . Escaper::quoteString($connection, $this->data) . ',' . Escaper::quoteString($connection, $this->username) . ');';
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
        $this->action = $resultSet->getStringValue("action");
        $this->loggedAt = $resultSet->getDateTime("logged_at");
        $this->objectId = $resultSet->getStringValue("object_id");
        $this->objectClass = $resultSet->getStringValue("object_class");
        $this->version = $resultSet->getIntegerValue("version");
        $this->data = $resultSet->getStringValue("data");
        $this->username = $resultSet->getStringValue("username");
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
        $connection->execute("CALL ext_log_entries_DB_PK($id)");
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
        $this->setAction($arrayAccessor->getStringValue("action"));
        $this->setLoggedAt($arrayAccessor->getDateTime("loggedAt"));
        $this->setObjectId($arrayAccessor->getStringValue("objectId"));
        $this->setObjectClass($arrayAccessor->getStringValue("objectClass"));
        $this->setVersion($arrayAccessor->getIntegerValue("version"));
        $this->setData($arrayAccessor->getStringValue("data"));
        $this->setUsername($arrayAccessor->getStringValue("username"));
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
            "action" => $this->getAction(),
            "loggedAt" => ($this->getLoggedAt() !== null) ? $this->getLoggedAt()->getJSONDateTime() : null,
            "objectId" => $this->getObjectId(),
            "objectClass" => $this->getObjectClass(),
            "version" => $this->getVersion(),
            "data" => $this->getData(),
            "username" => $this->getUsername()
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * 
     * @return void
     */
    public function setAction(string $action = null)
    {
        $this->action = StringUtil::trimToNull($action, 8);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * @param SiestaDateTime $loggedAt
     * 
     * @return void
     */
    public function setLoggedAt(SiestaDateTime $loggedAt = null)
    {
        $this->loggedAt = $loggedAt;
    }

    /**
     * 
     * @return string|null
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param string $objectId
     * 
     * @return void
     */
    public function setObjectId(string $objectId = null)
    {
        $this->objectId = StringUtil::trimToNull($objectId, 64);
    }

    /**
     * 
     * @return string|null
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     * 
     * @return void
     */
    public function setObjectClass(string $objectClass = null)
    {
        $this->objectClass = StringUtil::trimToNull($objectClass, 255);
    }

    /**
     * 
     * @return int|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     * 
     * @return void
     */
    public function setVersion(int $version = null)
    {
        $this->version = $version;
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
     * 
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * 
     * @return void
     */
    public function setUsername(string $username = null)
    {
        $this->username = StringUtil::trimToNull($username, 255);
    }

    /**
     * @param ExtLogEntries $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(ExtLogEntries $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}