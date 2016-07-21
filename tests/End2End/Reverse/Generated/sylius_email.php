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

class sylius_email implements ArraySerializable
{

    const TABLE_NAME = "sylius_email";

    const COLUMN_ID = "id";

    const COLUMN_CODE = "code";

    const COLUMN_TEMPLATE = "template";

    const COLUMN_ENABLED = "enabled";

    const COLUMN_SUBJECT = "subject";

    const COLUMN_SENDER_NAME = "sender_name";

    const COLUMN_SENDER_ADDRESS = "sender_address";

    const COLUMN_CONTENT = "content";

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
    protected $sender_name;

    /**
     * @var string
     */
    protected $sender_address;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteString($connection, $this->template) . ',' . Escaper::quoteString($connection, $this->enabled) . ',' . Escaper::quoteString($connection, $this->subject) . ',' . Escaper::quoteString($connection, $this->sender_name) . ',' . Escaper::quoteString($connection, $this->sender_address) . ',' . Escaper::quoteString($connection, $this->content) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        $this->sender_name = $resultSet->getStringValue("sender_name");
        $this->sender_address = $resultSet->getStringValue("sender_address");
        $this->content = $resultSet->getStringValue("content");
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
        $this->setSender_name($arrayAccessor->getStringValue("sender_name"));
        $this->setSender_address($arrayAccessor->getStringValue("sender_address"));
        $this->setContent($arrayAccessor->getStringValue("content"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
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
            "sender_name" => $this->getSender_name(),
            "sender_address" => $this->getSender_address(),
            "content" => $this->getContent(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
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
    public function getSender_name()
    {
        return $this->sender_name;
    }

    /**
     * @param string $sender_name
     * 
     * @return void
     */
    public function setSender_name(string $sender_name = null)
    {
        $this->sender_name = StringUtil::trimToNull($sender_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getSender_address()
    {
        return $this->sender_address;
    }

    /**
     * @param string $sender_address
     * 
     * @return void
     */
    public function setSender_address(string $sender_address = null)
    {
        $this->sender_address = StringUtil::trimToNull($sender_address, 255);
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
     * @param sylius_email $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_email $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}