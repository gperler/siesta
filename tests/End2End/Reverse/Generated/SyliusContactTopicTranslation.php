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
use Siesta\Util\StringUtil;

class SyliusContactTopicTranslation implements ArraySerializable
{

    const TABLE_NAME = "sylius_contact_topic_translation";

    const COLUMN_ID = "id";

    const COLUMN_TRANSLATABLEID = "translatable_id";

    const COLUMN_TITLE = "title";

    const COLUMN_LOCALE = "locale";

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
    protected $translatableId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var SyliusContactTopic
     */
    protected $6681216F2C2AC5D3;

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
        $spCall = ($this->_existing) ? "CALL sylius_contact_topic_translation_U(" : "CALL sylius_contact_topic_translation_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->translatableId) . ',' . Escaper::quoteString($connection, $this->title) . ',' . Escaper::quoteString($connection, $this->locale) . ');';
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
        if ($cascade && $this->6681216F2C2AC5D3 !== null) {
            $this->6681216F2C2AC5D3->save($cascade, $cycleDetector, $connectionName);
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
        $this->translatableId = $resultSet->getIntegerValue("translatable_id");
        $this->title = $resultSet->getStringValue("title");
        $this->locale = $resultSet->getStringValue("locale");
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
        $connection->execute("CALL sylius_contact_topic_translation_DB_PK($id)");
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
        $this->setTranslatableId($arrayAccessor->getIntegerValue("translatableId"));
        $this->setTitle($arrayAccessor->getStringValue("title"));
        $this->setLocale($arrayAccessor->getStringValue("locale"));
        $this->_existing = ($this->id !== null);
        $6681216F2C2AC5D3Array = $arrayAccessor->getArray("6681216F2C2AC5D3");
        if ($6681216F2C2AC5D3Array !== null) {
            $6681216F2C2AC5D3 = SyliusContactTopicService::getInstance()->newInstance();
            $6681216F2C2AC5D3->fromArray($6681216F2C2AC5D3Array);
            $this->set6681216F2C2AC5D3($6681216F2C2AC5D3);
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
            "translatableId" => $this->getTranslatableId(),
            "title" => $this->getTitle(),
            "locale" => $this->getLocale()
        ];
        if ($this->6681216F2C2AC5D3 !== null) {
            $result["6681216F2C2AC5D3"] = $this->6681216F2C2AC5D3->toArray($cycleDetector);
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
    public function getTranslatableId()
    {
        return $this->translatableId;
    }

    /**
     * @param int $translatableId
     * 
     * @return void
     */
    public function setTranslatableId(int $translatableId = null)
    {
        $this->translatableId = $translatableId;
    }

    /**
     * 
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * 
     * @return void
     */
    public function setTitle(string $title = null)
    {
        $this->title = StringUtil::trimToNull($title, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * 
     * @return void
     */
    public function setLocale(string $locale = null)
    {
        $this->locale = StringUtil::trimToNull($locale, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusContactTopic|null
     */
    public function get6681216F2C2AC5D3(bool $forceReload = false)
    {
        if ($this->6681216F2C2AC5D3 === null || $forceReload) {
            $this->6681216F2C2AC5D3 = SyliusContactTopicService::getInstance()->getEntityByPrimaryKey($this->translatableId);
        }
        return $this->6681216F2C2AC5D3;
    }

    /**
     * @param SyliusContactTopic $entity
     * 
     * @return void
     */
    public function set6681216F2C2AC5D3(SyliusContactTopic $entity = null)
    {
        $this->6681216F2C2AC5D3 = $entity;
        $this->translatableId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusContactTopicTranslation $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusContactTopicTranslation $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}