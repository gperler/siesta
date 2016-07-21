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

class SyliusOrderComment implements ArraySerializable
{

    const TABLE_NAME = "sylius_order_comment";

    const COLUMN_ID = "id";

    const COLUMN_ORDERID = "order_id";

    const COLUMN_STATE = "state";

    const COLUMN_COMMENT = "comment";

    const COLUMN_NOTIFYCUSTOMER = "notify_customer";

    const COLUMN_AUTHOR = "author";

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
    protected $orderId;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var string
     */
    protected $notifyCustomer;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusOrder
     */
    protected $8EA9CF098D9F6D38;

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
        $spCall = ($this->_existing) ? "CALL sylius_order_comment_U(" : "CALL sylius_order_comment_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->orderId) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteString($connection, $this->comment) . ',' . Escaper::quoteString($connection, $this->notifyCustomer) . ',' . Escaper::quoteString($connection, $this->author) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        if ($cascade && $this->8EA9CF098D9F6D38 !== null) {
            $this->8EA9CF098D9F6D38->save($cascade, $cycleDetector, $connectionName);
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
        $this->orderId = $resultSet->getIntegerValue("order_id");
        $this->state = $resultSet->getStringValue("state");
        $this->comment = $resultSet->getStringValue("comment");
        $this->notifyCustomer = $resultSet->getStringValue("notify_customer");
        $this->author = $resultSet->getStringValue("author");
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
        $connection->execute("CALL sylius_order_comment_DB_PK($id)");
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
        $this->setOrderId($arrayAccessor->getIntegerValue("orderId"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setComment($arrayAccessor->getStringValue("comment"));
        $this->setNotifyCustomer($arrayAccessor->getStringValue("notifyCustomer"));
        $this->setAuthor($arrayAccessor->getStringValue("author"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $8EA9CF098D9F6D38Array = $arrayAccessor->getArray("8EA9CF098D9F6D38");
        if ($8EA9CF098D9F6D38Array !== null) {
            $8EA9CF098D9F6D38 = SyliusOrderService::getInstance()->newInstance();
            $8EA9CF098D9F6D38->fromArray($8EA9CF098D9F6D38Array);
            $this->set8EA9CF098D9F6D38($8EA9CF098D9F6D38);
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
            "orderId" => $this->getOrderId(),
            "state" => $this->getState(),
            "comment" => $this->getComment(),
            "notifyCustomer" => $this->getNotifyCustomer(),
            "author" => $this->getAuthor(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
        ];
        if ($this->8EA9CF098D9F6D38 !== null) {
            $result["8EA9CF098D9F6D38"] = $this->8EA9CF098D9F6D38->toArray($cycleDetector);
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
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     * 
     * @return void
     */
    public function setOrderId(int $orderId = null)
    {
        $this->orderId = $orderId;
    }

    /**
     * 
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * 
     * @return void
     */
    public function setState(string $state = null)
    {
        $this->state = StringUtil::trimToNull($state, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * 
     * @return void
     */
    public function setComment(string $comment = null)
    {
        $this->comment = StringUtil::trimToNull($comment, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getNotifyCustomer()
    {
        return $this->notifyCustomer;
    }

    /**
     * @param string $notifyCustomer
     * 
     * @return void
     */
    public function setNotifyCustomer(string $notifyCustomer = null)
    {
        $this->notifyCustomer = StringUtil::trimToNull($notifyCustomer, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     * 
     * @return void
     */
    public function setAuthor(string $author = null)
    {
        $this->author = StringUtil::trimToNull($author, 255);
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
     * @return SyliusOrder|null
     */
    public function get8EA9CF098D9F6D38(bool $forceReload = false)
    {
        if ($this->8EA9CF098D9F6D38 === null || $forceReload) {
            $this->8EA9CF098D9F6D38 = SyliusOrderService::getInstance()->getEntityByPrimaryKey($this->orderId);
        }
        return $this->8EA9CF098D9F6D38;
    }

    /**
     * @param SyliusOrder $entity
     * 
     * @return void
     */
    public function set8EA9CF098D9F6D38(SyliusOrder $entity = null)
    {
        $this->8EA9CF098D9F6D38 = $entity;
        $this->orderId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusOrderComment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusOrderComment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}