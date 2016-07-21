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

class sylius_order_comment implements ArraySerializable
{

    const TABLE_NAME = "sylius_order_comment";

    const COLUMN_ID = "id";

    const COLUMN_ORDER_ID = "order_id";

    const COLUMN_STATE = "state";

    const COLUMN_COMMENT = "comment";

    const COLUMN_NOTIFY_CUSTOMER = "notify_customer";

    const COLUMN_AUTHOR = "author";

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
    protected $order_id;

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
    protected $notify_customer;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_order
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->order_id) . ',' . Escaper::quoteString($connection, $this->state) . ',' . Escaper::quoteString($connection, $this->comment) . ',' . Escaper::quoteString($connection, $this->notify_customer) . ',' . Escaper::quoteString($connection, $this->author) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        $this->order_id = $resultSet->getIntegerValue("order_id");
        $this->state = $resultSet->getStringValue("state");
        $this->comment = $resultSet->getStringValue("comment");
        $this->notify_customer = $resultSet->getStringValue("notify_customer");
        $this->author = $resultSet->getStringValue("author");
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
        $this->setOrder_id($arrayAccessor->getIntegerValue("order_id"));
        $this->setState($arrayAccessor->getStringValue("state"));
        $this->setComment($arrayAccessor->getStringValue("comment"));
        $this->setNotify_customer($arrayAccessor->getStringValue("notify_customer"));
        $this->setAuthor($arrayAccessor->getStringValue("author"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $8EA9CF098D9F6D38Array = $arrayAccessor->getArray("8EA9CF098D9F6D38");
        if ($8EA9CF098D9F6D38Array !== null) {
            $8EA9CF098D9F6D38 = sylius_orderService::getInstance()->newInstance();
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
            "order_id" => $this->getOrder_id(),
            "state" => $this->getState(),
            "comment" => $this->getComment(),
            "notify_customer" => $this->getNotify_customer(),
            "author" => $this->getAuthor(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
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
    public function getOrder_id()
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     * 
     * @return void
     */
    public function setOrder_id(int $order_id = null)
    {
        $this->order_id = $order_id;
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
    public function getNotify_customer()
    {
        return $this->notify_customer;
    }

    /**
     * @param string $notify_customer
     * 
     * @return void
     */
    public function setNotify_customer(string $notify_customer = null)
    {
        $this->notify_customer = StringUtil::trimToNull($notify_customer, null);
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
     * @return sylius_order|null
     */
    public function get8EA9CF098D9F6D38(bool $forceReload = false)
    {
        if ($this->8EA9CF098D9F6D38 === null || $forceReload) {
            $this->8EA9CF098D9F6D38 = sylius_orderService::getInstance()->getEntityByPrimaryKey($this->order_id);
        }
        return $this->8EA9CF098D9F6D38;
    }

    /**
     * @param sylius_order $entity
     * 
     * @return void
     */
    public function set8EA9CF098D9F6D38(sylius_order $entity = null)
    {
        $this->8EA9CF098D9F6D38 = $entity;
        $this->order_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_order_comment $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_order_comment $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}