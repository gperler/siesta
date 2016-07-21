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

class SyliusChannelLocales implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_locales";

    const COLUMN_CHANNELID = "channel_id";

    const COLUMN_LOCALEID = "locale_id";

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
    protected $channelId;

    /**
     * @var int
     */
    protected $localeId;

    /**
     * @var SyliusChannel
     */
    protected $786B7A8472F5A1AA;

    /**
     * @var SyliusLocale
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
        $this->getChannelId(true, $connectionName);
        $this->getLocaleId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channelId) . ',' . Escaper::quoteInt($this->localeId) . ');';
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
        $this->channelId = $resultSet->getIntegerValue("channel_id");
        $this->localeId = $resultSet->getIntegerValue("locale_id");
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
        $channelId = Escaper::quoteInt($this->channelId);
        $localeId = Escaper::quoteInt($this->localeId);
        $connection->execute("CALL sylius_channel_locales_DB_PK($channelId,$localeId)");
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
        $this->setChannelId($arrayAccessor->getIntegerValue("channelId"));
        $this->setLocaleId($arrayAccessor->getIntegerValue("localeId"));
        $this->_existing = ($this->channelId !== null) && ($this->localeId !== null);
        $786B7A8472F5A1AAArray = $arrayAccessor->getArray("786B7A8472F5A1AA");
        if ($786B7A8472F5A1AAArray !== null) {
            $786B7A8472F5A1AA = SyliusChannelService::getInstance()->newInstance();
            $786B7A8472F5A1AA->fromArray($786B7A8472F5A1AAArray);
            $this->set786B7A8472F5A1AA($786B7A8472F5A1AA);
        }
        $786B7A84E559DFD1Array = $arrayAccessor->getArray("786B7A84E559DFD1");
        if ($786B7A84E559DFD1Array !== null) {
            $786B7A84E559DFD1 = SyliusLocaleService::getInstance()->newInstance();
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
            "channelId" => $this->getChannelId(),
            "localeId" => $this->getLocaleId()
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
    public function getChannelId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->channelId === null) {
            $this->channelId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->channelId;
    }

    /**
     * @param int $channelId
     * 
     * @return void
     */
    public function setChannelId(int $channelId = null)
    {
        $this->channelId = $channelId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getLocaleId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->localeId === null) {
            $this->localeId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->localeId;
    }

    /**
     * @param int $localeId
     * 
     * @return void
     */
    public function setLocaleId(int $localeId = null)
    {
        $this->localeId = $localeId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function get786B7A8472F5A1AA(bool $forceReload = false)
    {
        if ($this->786B7A8472F5A1AA === null || $forceReload) {
            $this->786B7A8472F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->786B7A8472F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function set786B7A8472F5A1AA(SyliusChannel $entity = null)
    {
        $this->786B7A8472F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusLocale|null
     */
    public function get786B7A84E559DFD1(bool $forceReload = false)
    {
        if ($this->786B7A84E559DFD1 === null || $forceReload) {
            $this->786B7A84E559DFD1 = SyliusLocaleService::getInstance()->getEntityByPrimaryKey($this->localeId);
        }
        return $this->786B7A84E559DFD1;
    }

    /**
     * @param SyliusLocale $entity
     * 
     * @return void
     */
    public function set786B7A84E559DFD1(SyliusLocale $entity = null)
    {
        $this->786B7A84E559DFD1 = $entity;
        $this->localeId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusChannelLocales $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusChannelLocales $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannelId() === $entity->getChannelId() && $this->getLocaleId() === $entity->getLocaleId();
    }

}