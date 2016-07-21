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

class SyliusContactRequest implements ArraySerializable
{

    const TABLE_NAME = "sylius_contact_request";

    const COLUMN_ID = "id";

    const COLUMN_TOPICID = "topic_id";

    const COLUMN_FIRSTNAME = "first_name";

    const COLUMN_LASTNAME = "last_name";

    const COLUMN_EMAIL = "email";

    const COLUMN_MESSAGE = "message";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

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
    protected $topicId;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

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
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusContactTopic
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->topicId) . ',' . Escaper::quoteString($connection, $this->firstName) . ',' . Escaper::quoteString($connection, $this->lastName) . ',' . Escaper::quoteString($connection, $this->email) . ',' . Escaper::quoteString($connection, $this->message) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        $this->topicId = $resultSet->getIntegerValue("topic_id");
        $this->firstName = $resultSet->getStringValue("first_name");
        $this->lastName = $resultSet->getStringValue("last_name");
        $this->email = $resultSet->getStringValue("email");
        $this->message = $resultSet->getStringValue("message");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
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
        $this->setTopicId($arrayAccessor->getIntegerValue("topicId"));
        $this->setFirstName($arrayAccessor->getStringValue("firstName"));
        $this->setLastName($arrayAccessor->getStringValue("lastName"));
        $this->setEmail($arrayAccessor->getStringValue("email"));
        $this->setMessage($arrayAccessor->getStringValue("message"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $8B0BBF201F55203DArray = $arrayAccessor->getArray("8B0BBF201F55203D");
        if ($8B0BBF201F55203DArray !== null) {
            $8B0BBF201F55203D = SyliusContactTopicService::getInstance()->newInstance();
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
            "topicId" => $this->getTopicId(),
            "firstName" => $this->getFirstName(),
            "lastName" => $this->getLastName(),
            "email" => $this->getEmail(),
            "message" => $this->getMessage(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
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
    public function getTopicId()
    {
        return $this->topicId;
    }

    /**
     * @param int $topicId
     * 
     * @return void
     */
    public function setTopicId(int $topicId = null)
    {
        $this->topicId = $topicId;
    }

    /**
     * 
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * 
     * @return void
     */
    public function setFirstName(string $firstName = null)
    {
        $this->firstName = StringUtil::trimToNull($firstName, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * 
     * @return void
     */
    public function setLastName(string $lastName = null)
    {
        $this->lastName = StringUtil::trimToNull($lastName, 255);
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param SiestaDateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(SiestaDateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param SiestaDateTime $updatedAt
     * 
     * @return void
     */
    public function setUpdatedAt(SiestaDateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusContactTopic|null
     */
    public function get8B0BBF201F55203D(bool $forceReload = false)
    {
        if ($this->8B0BBF201F55203D === null || $forceReload) {
            $this->8B0BBF201F55203D = SyliusContactTopicService::getInstance()->getEntityByPrimaryKey($this->topicId);
        }
        return $this->8B0BBF201F55203D;
    }

    /**
     * @param SyliusContactTopic $entity
     * 
     * @return void
     */
    public function set8B0BBF201F55203D(SyliusContactTopic $entity = null)
    {
        $this->8B0BBF201F55203D = $entity;
        $this->topicId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusContactRequest $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusContactRequest $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}