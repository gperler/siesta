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

class sylius_contact_request implements ArraySerializable
{

    const TABLE_NAME = "sylius_contact_request";

    const COLUMN_ID = "id";

    const COLUMN_TOPIC_ID = "topic_id";

    const COLUMN_FIRST_NAME = "first_name";

    const COLUMN_LAST_NAME = "last_name";

    const COLUMN_EMAIL = "email";

    const COLUMN_MESSAGE = "message";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

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
    protected $topic_id;

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_contact_topic
     */
    protected $8B0BBF201F55203D;

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
        $spCall = ($this->_existing) ? "CALL sylius_contact_request_U(" : "CALL sylius_contact_request_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->topic_id) . ',' . Escaper::quoteString($connection, $this->first_name) . ',' . Escaper::quoteString($connection, $this->last_name) . ',' . Escaper::quoteString($connection, $this->email) . ',' . Escaper::quoteString($connection, $this->message) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        if ($cascade && $this->8B0BBF201F55203D !== null) {
            $this->8B0BBF201F55203D->save($cascade, $cycleDetector, $connectionName);
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
        $this->topic_id = $resultSet->getIntegerValue("topic_id");
        $this->first_name = $resultSet->getStringValue("first_name");
        $this->last_name = $resultSet->getStringValue("last_name");
        $this->email = $resultSet->getStringValue("email");
        $this->message = $resultSet->getStringValue("message");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
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
        $connection->execute("CALL sylius_contact_request_DB_PK($id)");
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
        $this->setTopic_id($arrayAccessor->getIntegerValue("topic_id"));
        $this->setFirst_name($arrayAccessor->getStringValue("first_name"));
        $this->setLast_name($arrayAccessor->getStringValue("last_name"));
        $this->setEmail($arrayAccessor->getStringValue("email"));
        $this->setMessage($arrayAccessor->getStringValue("message"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $8B0BBF201F55203DArray = $arrayAccessor->getArray("8B0BBF201F55203D");
        if ($8B0BBF201F55203DArray !== null) {
            $8B0BBF201F55203D = sylius_contact_topicService::getInstance()->newInstance();
            $8B0BBF201F55203D->fromArray($8B0BBF201F55203DArray);
            $this->set8B0BBF201F55203D($8B0BBF201F55203D);
        }
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
            "topic_id" => $this->getTopic_id(),
            "first_name" => $this->getFirst_name(),
            "last_name" => $this->getLast_name(),
            "email" => $this->getEmail(),
            "message" => $this->getMessage(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
        ];
        if ($this->8B0BBF201F55203D !== null) {
            $result["8B0BBF201F55203D"] = $this->8B0BBF201F55203D->toArray($cycleDetector);
        }
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
    public function getTopic_id()
    {
        return $this->topic_id;
    }

    /**
     * @param int $topic_id
     * 
     * @return void
     */
    public function setTopic_id(int $topic_id = null)
    {
        $this->topic_id = $topic_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * 
     * @return void
     */
    public function setFirst_name(string $first_name = null)
    {
        $this->first_name = StringUtil::trimToNull($first_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLast_name()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * 
     * @return void
     */
    public function setLast_name(string $last_name = null)
    {
        $this->last_name = StringUtil::trimToNull($last_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * 
     * @return void
     */
    public function setEmail(string $email = null)
    {
        $this->email = StringUtil::trimToNull($email, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * 
     * @return void
     */
    public function setMessage(string $message = null)
    {
        $this->message = StringUtil::trimToNull($message, null);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * @param SiestaDateTime $created_at
     * 
     * @return void
     */
    public function setCreated_at(SiestaDateTime $created_at = null)
    {
        $this->created_at = $created_at;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * @param SiestaDateTime $updated_at
     * 
     * @return void
     */
    public function setUpdated_at(SiestaDateTime $updated_at = null)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_contact_topic|null
     */
    public function get8B0BBF201F55203D(bool $forceReload = false)
    {
        if ($this->8B0BBF201F55203D === null || $forceReload) {
            $this->8B0BBF201F55203D = sylius_contact_topicService::getInstance()->getEntityByPrimaryKey($this->topic_id);
        }
        return $this->8B0BBF201F55203D;
    }

    /**
     * @param sylius_contact_topic $entity
     * 
     * @return void
     */
    public function set8B0BBF201F55203D(sylius_contact_topic $entity = null)
    {
        $this->8B0BBF201F55203D = $entity;
        $this->topic_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_contact_request $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_contact_request $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}