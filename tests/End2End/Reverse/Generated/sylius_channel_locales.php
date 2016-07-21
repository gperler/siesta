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

class sylius_channel_locales implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_locales";

    const COLUMN_CHANNEL_ID = "channel_id";

    const COLUMN_LOCALE_ID = "locale_id";

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
    protected $channel_id;

    /**
     * @var int
     */
    protected $locale_id;

    /**
     * @var sylius_channel
     */
    protected $786B7A8472F5A1AA;

    /**
     * @var sylius_locale
     */
    protected $786B7A84E559DFD1;

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
        $spCall = ($this->_existing) ? "CALL sylius_channel_locales_U(" : "CALL sylius_channel_locales_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getChannel_id(true, $connectionName);
        $this->getLocale_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channel_id) . ',' . Escaper::quoteInt($this->locale_id) . ');';
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
        if ($cascade && $this->786B7A8472F5A1AA !== null) {
            $this->786B7A8472F5A1AA->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->786B7A84E559DFD1 !== null) {
            $this->786B7A84E559DFD1->save($cascade, $cycleDetector, $connectionName);
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
        $this->channel_id = $resultSet->getIntegerValue("channel_id");
        $this->locale_id = $resultSet->getIntegerValue("locale_id");
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
        $channel_id = Escaper::quoteInt($this->channel_id);
        $locale_id = Escaper::quoteInt($this->locale_id);
        $connection->execute("CALL sylius_channel_locales_DB_PK($channel_id,$locale_id)");
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
        $this->setChannel_id($arrayAccessor->getIntegerValue("channel_id"));
        $this->setLocale_id($arrayAccessor->getIntegerValue("locale_id"));
        $this->_existing = ($this->channel_id !== null) && ($this->locale_id !== null);
        $786B7A8472F5A1AAArray = $arrayAccessor->getArray("786B7A8472F5A1AA");
        if ($786B7A8472F5A1AAArray !== null) {
            $786B7A8472F5A1AA = sylius_channelService::getInstance()->newInstance();
            $786B7A8472F5A1AA->fromArray($786B7A8472F5A1AAArray);
            $this->set786B7A8472F5A1AA($786B7A8472F5A1AA);
        }
        $786B7A84E559DFD1Array = $arrayAccessor->getArray("786B7A84E559DFD1");
        if ($786B7A84E559DFD1Array !== null) {
            $786B7A84E559DFD1 = sylius_localeService::getInstance()->newInstance();
            $786B7A84E559DFD1->fromArray($786B7A84E559DFD1Array);
            $this->set786B7A84E559DFD1($786B7A84E559DFD1);
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
            "channel_id" => $this->getChannel_id(),
            "locale_id" => $this->getLocale_id()
        ];
        if ($this->786B7A8472F5A1AA !== null) {
            $result["786B7A8472F5A1AA"] = $this->786B7A8472F5A1AA->toArray($cycleDetector);
        }
        if ($this->786B7A84E559DFD1 !== null) {
            $result["786B7A84E559DFD1"] = $this->786B7A84E559DFD1->toArray($cycleDetector);
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
    public function getChannel_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->channel_id === null) {
            $this->channel_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->channel_id;
    }

    /**
     * @param int $channel_id
     * 
     * @return void
     */
    public function setChannel_id(int $channel_id = null)
    {
        $this->channel_id = $channel_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getLocale_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->locale_id === null) {
            $this->locale_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->locale_id;
    }

    /**
     * @param int $locale_id
     * 
     * @return void
     */
    public function setLocale_id(int $locale_id = null)
    {
        $this->locale_id = $locale_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_channel|null
     */
    public function get786B7A8472F5A1AA(bool $forceReload = false)
    {
        if ($this->786B7A8472F5A1AA === null || $forceReload) {
            $this->786B7A8472F5A1AA = sylius_channelService::getInstance()->getEntityByPrimaryKey($this->channel_id);
        }
        return $this->786B7A8472F5A1AA;
    }

    /**
     * @param sylius_channel $entity
     * 
     * @return void
     */
    public function set786B7A8472F5A1AA(sylius_channel $entity = null)
    {
        $this->786B7A8472F5A1AA = $entity;
        $this->channel_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_locale|null
     */
    public function get786B7A84E559DFD1(bool $forceReload = false)
    {
        if ($this->786B7A84E559DFD1 === null || $forceReload) {
            $this->786B7A84E559DFD1 = sylius_localeService::getInstance()->getEntityByPrimaryKey($this->locale_id);
        }
        return $this->786B7A84E559DFD1;
    }

    /**
     * @param sylius_locale $entity
     * 
     * @return void
     */
    public function set786B7A84E559DFD1(sylius_locale $entity = null)
    {
        $this->786B7A84E559DFD1 = $entity;
        $this->locale_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_channel_locales $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_channel_locales $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannel_id() === $entity->getChannel_id() && $this->getLocale_id() === $entity->getLocale_id();
    }

}