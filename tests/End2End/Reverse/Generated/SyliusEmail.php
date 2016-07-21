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

class SyliusEmail implements ArraySerializable
{

    const TABLE_NAME = "sylius_email";

    const COLUMN_ID = "id";

    const COLUMN_CODE = "code";

    const COLUMN_TEMPLATE = "template";

    const COLUMN_ENABLED = "enabled";

    const COLUMN_SUBJECT = "subject";

    const COLUMN_SENDERNAME = "sender_name";

    const COLUMN_SENDERADDRESS = "sender_address";

    const COLUMN_CONTENT = "content";

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
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var string
     */
    protected $senderAddress;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

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
        $spCall = ($this->_existing) ? "CALL sylius_email_U(" : "CALL sylius_email_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteString($connection, $this->template) . ',' . Escaper::quoteString($connection, $this->enabled) . ',' . Escaper::quoteString($connection, $this->subject) . ',' . Escaper::quoteString($connection, $this->senderName) . ',' . Escaper::quoteString($connection, $this->senderAddress) . ',' . Escaper::quoteString($connection, $this->content) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        $this->code = $resultSet->getStringValue("code");
        $this->template = $resultSet->getStringValue("template");
        $this->enabled = $resultSet->getStringValue("enabled");
        $this->subject = $resultSet->getStringValue("subject");
        $this->senderName = $resultSet->getStringValue("sender_name");
        $this->senderAddress = $resultSet->getStringValue("sender_address");
        $this->content = $resultSet->getStringValue("content");
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
        $connection->execute("CALL sylius_email_DB_PK($id)");
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
        $this->setCode($arrayAccessor->getStringValue("code"));
        $this->setTemplate($arrayAccessor->getStringValue("template"));
        $this->setEnabled($arrayAccessor->getStringValue("enabled"));
        $this->setSubject($arrayAccessor->getStringValue("subject"));
        $this->setSenderName($arrayAccessor->getStringValue("senderName"));
        $this->setSenderAddress($arrayAccessor->getStringValue("senderAddress"));
        $this->setContent($arrayAccessor->getStringValue("content"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
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
            "code" => $this->getCode(),
            "template" => $this->getTemplate(),
            "enabled" => $this->getEnabled(),
            "subject" => $this->getSubject(),
            "senderName" => $this->getSenderName(),
            "senderAddress" => $this->getSenderAddress(),
            "content" => $this->getContent(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * 
     * @return void
     */
    public function setCode(string $code = null)
    {
        $this->code = StringUtil::trimToNull($code, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     * 
     * @return void
     */
    public function setTemplate(string $template = null)
    {
        $this->template = StringUtil::trimToNull($template, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $enabled
     * 
     * @return void
     */
    public function setEnabled(string $enabled = null)
    {
        $this->enabled = StringUtil::trimToNull($enabled, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * 
     * @return void
     */
    public function setSubject(string $subject = null)
    {
        $this->subject = StringUtil::trimToNull($subject, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     * 
     * @return void
     */
    public function setSenderName(string $senderName = null)
    {
        $this->senderName = StringUtil::trimToNull($senderName, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getSenderAddress()
    {
        return $this->senderAddress;
    }

    /**
     * @param string $senderAddress
     * 
     * @return void
     */
    public function setSenderAddress(string $senderAddress = null)
    {
        $this->senderAddress = StringUtil::trimToNull($senderAddress, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * 
     * @return void
     */
    public function setContent(string $content = null)
    {
        $this->content = StringUtil::trimToNull($content, null);
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
     * @param SyliusEmail $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusEmail $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}