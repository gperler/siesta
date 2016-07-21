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

class SyliusChannelTaxonomy implements ArraySerializable
{

    const TABLE_NAME = "sylius_channel_taxonomy";

    const COLUMN_CHANNELID = "channel_id";

    const COLUMN_TAXONOMYID = "taxonomy_id";

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
    protected $taxonomyId;

    /**
     * @var SyliusChannel
     */
    protected $4BE9652E72F5A1AA;

    /**
     * @var SyliusTaxonomy
     */
    protected $4BE9652E9557E6F6;

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
        $spCall = ($this->_existing) ? "CALL sylius_channel_taxonomy_U(" : "CALL sylius_channel_taxonomy_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getChannelId(true, $connectionName);
        $this->getTaxonomyId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->channelId) . ',' . Escaper::quoteInt($this->taxonomyId) . ');';
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
        if ($cascade && $this->4BE9652E72F5A1AA !== null) {
            $this->4BE9652E72F5A1AA->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->4BE9652E9557E6F6 !== null) {
            $this->4BE9652E9557E6F6->save($cascade, $cycleDetector, $connectionName);
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
        $this->taxonomyId = $resultSet->getIntegerValue("taxonomy_id");
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
        $taxonomyId = Escaper::quoteInt($this->taxonomyId);
        $connection->execute("CALL sylius_channel_taxonomy_DB_PK($channelId,$taxonomyId)");
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
        $this->setTaxonomyId($arrayAccessor->getIntegerValue("taxonomyId"));
        $this->_existing = ($this->channelId !== null) && ($this->taxonomyId !== null);
        $4BE9652E72F5A1AAArray = $arrayAccessor->getArray("4BE9652E72F5A1AA");
        if ($4BE9652E72F5A1AAArray !== null) {
            $4BE9652E72F5A1AA = SyliusChannelService::getInstance()->newInstance();
            $4BE9652E72F5A1AA->fromArray($4BE9652E72F5A1AAArray);
            $this->set4BE9652E72F5A1AA($4BE9652E72F5A1AA);
        }
        $4BE9652E9557E6F6Array = $arrayAccessor->getArray("4BE9652E9557E6F6");
        if ($4BE9652E9557E6F6Array !== null) {
            $4BE9652E9557E6F6 = SyliusTaxonomyService::getInstance()->newInstance();
            $4BE9652E9557E6F6->fromArray($4BE9652E9557E6F6Array);
            $this->set4BE9652E9557E6F6($4BE9652E9557E6F6);
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
            "taxonomyId" => $this->getTaxonomyId()
        ];
        if ($this->4BE9652E72F5A1AA !== null) {
            $result["4BE9652E72F5A1AA"] = $this->4BE9652E72F5A1AA->toArray($cycleDetector);
        }
        if ($this->4BE9652E9557E6F6 !== null) {
            $result["4BE9652E9557E6F6"] = $this->4BE9652E9557E6F6->toArray($cycleDetector);
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
    public function getTaxonomyId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->taxonomyId === null) {
            $this->taxonomyId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->taxonomyId;
    }

    /**
     * @param int $taxonomyId
     * 
     * @return void
     */
    public function setTaxonomyId(int $taxonomyId = null)
    {
        $this->taxonomyId = $taxonomyId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusChannel|null
     */
    public function get4BE9652E72F5A1AA(bool $forceReload = false)
    {
        if ($this->4BE9652E72F5A1AA === null || $forceReload) {
            $this->4BE9652E72F5A1AA = SyliusChannelService::getInstance()->getEntityByPrimaryKey($this->channelId);
        }
        return $this->4BE9652E72F5A1AA;
    }

    /**
     * @param SyliusChannel $entity
     * 
     * @return void
     */
    public function set4BE9652E72F5A1AA(SyliusChannel $entity = null)
    {
        $this->4BE9652E72F5A1AA = $entity;
        $this->channelId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusTaxonomy|null
     */
    public function get4BE9652E9557E6F6(bool $forceReload = false)
    {
        if ($this->4BE9652E9557E6F6 === null || $forceReload) {
            $this->4BE9652E9557E6F6 = SyliusTaxonomyService::getInstance()->getEntityByPrimaryKey($this->taxonomyId);
        }
        return $this->4BE9652E9557E6F6;
    }

    /**
     * @param SyliusTaxonomy $entity
     * 
     * @return void
     */
    public function set4BE9652E9557E6F6(SyliusTaxonomy $entity = null)
    {
        $this->4BE9652E9557E6F6 = $entity;
        $this->taxonomyId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusChannelTaxonomy $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusChannelTaxonomy $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getChannelId() === $entity->getChannelId() && $this->getTaxonomyId() === $entity->getTaxonomyId();
    }

}