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

class SyliusZoneMember implements ArraySerializable
{

    const TABLE_NAME = "sylius_zone_member";

    const COLUMN_ID = "id";

    const COLUMN_BELONGSTO = "belongs_to";

    const COLUMN_COUNTRYID = "country_id";

    const COLUMN_PROVINCEID = "province_id";

    const COLUMN_ZONEID = "zone_id";

    const COLUMN_TYPE = "type";

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
    protected $belongsTo;

    /**
     * @var int
     */
    protected $countryId;

    /**
     * @var int
     */
    protected $provinceId;

    /**
     * @var int
     */
    protected $zoneId;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var SyliusZone
     */
    protected $E8B5ABF34B0E929B;

    /**
     * @var SyliusZone
     */
    protected $E8B5ABF39F2C3FAB;

    /**
     * @var SyliusProvince
     */
    protected $E8B5ABF3E946114A;

    /**
     * @var SyliusCountry
     */
    protected $E8B5ABF3F92F3E70;

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
        $spCall = ($this->_existing) ? "CALL sylius_zone_member_U(" : "CALL sylius_zone_member_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->belongsTo) . ',' . Escaper::quoteInt($this->countryId) . ',' . Escaper::quoteInt($this->provinceId) . ',' . Escaper::quoteInt($this->zoneId) . ',' . Escaper::quoteString($connection, $this->type) . ');';
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
        if ($cascade && $this->E8B5ABF34B0E929B !== null) {
            $this->E8B5ABF34B0E929B->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->E8B5ABF39F2C3FAB !== null) {
            $this->E8B5ABF39F2C3FAB->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->E8B5ABF3E946114A !== null) {
            $this->E8B5ABF3E946114A->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->E8B5ABF3F92F3E70 !== null) {
            $this->E8B5ABF3F92F3E70->save($cascade, $cycleDetector, $connectionName);
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
        $this->belongsTo = $resultSet->getIntegerValue("belongs_to");
        $this->countryId = $resultSet->getIntegerValue("country_id");
        $this->provinceId = $resultSet->getIntegerValue("province_id");
        $this->zoneId = $resultSet->getIntegerValue("zone_id");
        $this->type = $resultSet->getStringValue("type");
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
        $connection->execute("CALL sylius_zone_member_DB_PK($id)");
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
        $this->setBelongsTo($arrayAccessor->getIntegerValue("belongsTo"));
        $this->setCountryId($arrayAccessor->getIntegerValue("countryId"));
        $this->setProvinceId($arrayAccessor->getIntegerValue("provinceId"));
        $this->setZoneId($arrayAccessor->getIntegerValue("zoneId"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->_existing = ($this->id !== null);
        $E8B5ABF34B0E929BArray = $arrayAccessor->getArray("E8B5ABF34B0E929B");
        if ($E8B5ABF34B0E929BArray !== null) {
            $E8B5ABF34B0E929B = SyliusZoneService::getInstance()->newInstance();
            $E8B5ABF34B0E929B->fromArray($E8B5ABF34B0E929BArray);
            $this->setE8B5ABF34B0E929B($E8B5ABF34B0E929B);
        }
        $E8B5ABF39F2C3FABArray = $arrayAccessor->getArray("E8B5ABF39F2C3FAB");
        if ($E8B5ABF39F2C3FABArray !== null) {
            $E8B5ABF39F2C3FAB = SyliusZoneService::getInstance()->newInstance();
            $E8B5ABF39F2C3FAB->fromArray($E8B5ABF39F2C3FABArray);
            $this->setE8B5ABF39F2C3FAB($E8B5ABF39F2C3FAB);
        }
        $E8B5ABF3E946114AArray = $arrayAccessor->getArray("E8B5ABF3E946114A");
        if ($E8B5ABF3E946114AArray !== null) {
            $E8B5ABF3E946114A = SyliusProvinceService::getInstance()->newInstance();
            $E8B5ABF3E946114A->fromArray($E8B5ABF3E946114AArray);
            $this->setE8B5ABF3E946114A($E8B5ABF3E946114A);
        }
        $E8B5ABF3F92F3E70Array = $arrayAccessor->getArray("E8B5ABF3F92F3E70");
        if ($E8B5ABF3F92F3E70Array !== null) {
            $E8B5ABF3F92F3E70 = SyliusCountryService::getInstance()->newInstance();
            $E8B5ABF3F92F3E70->fromArray($E8B5ABF3F92F3E70Array);
            $this->setE8B5ABF3F92F3E70($E8B5ABF3F92F3E70);
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
            "belongsTo" => $this->getBelongsTo(),
            "countryId" => $this->getCountryId(),
            "provinceId" => $this->getProvinceId(),
            "zoneId" => $this->getZoneId(),
            "type" => $this->getType()
        ];
        if ($this->E8B5ABF34B0E929B !== null) {
            $result["E8B5ABF34B0E929B"] = $this->E8B5ABF34B0E929B->toArray($cycleDetector);
        }
        if ($this->E8B5ABF39F2C3FAB !== null) {
            $result["E8B5ABF39F2C3FAB"] = $this->E8B5ABF39F2C3FAB->toArray($cycleDetector);
        }
        if ($this->E8B5ABF3E946114A !== null) {
            $result["E8B5ABF3E946114A"] = $this->E8B5ABF3E946114A->toArray($cycleDetector);
        }
        if ($this->E8B5ABF3F92F3E70 !== null) {
            $result["E8B5ABF3F92F3E70"] = $this->E8B5ABF3F92F3E70->toArray($cycleDetector);
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
    public function getBelongsTo()
    {
        return $this->belongsTo;
    }

    /**
     * @param int $belongsTo
     * 
     * @return void
     */
    public function setBelongsTo(int $belongsTo = null)
    {
        $this->belongsTo = $belongsTo;
    }

    /**
     * 
     * @return int|null
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param int $countryId
     * 
     * @return void
     */
    public function setCountryId(int $countryId = null)
    {
        $this->countryId = $countryId;
    }

    /**
     * 
     * @return int|null
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * @param int $provinceId
     * 
     * @return void
     */
    public function setProvinceId(int $provinceId = null)
    {
        $this->provinceId = $provinceId;
    }

    /**
     * 
     * @return int|null
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * @param int $zoneId
     * 
     * @return void
     */
    public function setZoneId(int $zoneId = null)
    {
        $this->zoneId = $zoneId;
    }

    /**
     * 
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type = null)
    {
        $this->type = StringUtil::trimToNull($type, 8);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusZone|null
     */
    public function getE8B5ABF34B0E929B(bool $forceReload = false)
    {
        if ($this->E8B5ABF34B0E929B === null || $forceReload) {
            $this->E8B5ABF34B0E929B = SyliusZoneService::getInstance()->getEntityByPrimaryKey($this->belongsTo);
        }
        return $this->E8B5ABF34B0E929B;
    }

    /**
     * @param SyliusZone $entity
     * 
     * @return void
     */
    public function setE8B5ABF34B0E929B(SyliusZone $entity = null)
    {
        $this->E8B5ABF34B0E929B = $entity;
        $this->belongsTo = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusZone|null
     */
    public function getE8B5ABF39F2C3FAB(bool $forceReload = false)
    {
        if ($this->E8B5ABF39F2C3FAB === null || $forceReload) {
            $this->E8B5ABF39F2C3FAB = SyliusZoneService::getInstance()->getEntityByPrimaryKey($this->zoneId);
        }
        return $this->E8B5ABF39F2C3FAB;
    }

    /**
     * @param SyliusZone $entity
     * 
     * @return void
     */
    public function setE8B5ABF39F2C3FAB(SyliusZone $entity = null)
    {
        $this->E8B5ABF39F2C3FAB = $entity;
        $this->zoneId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProvince|null
     */
    public function getE8B5ABF3E946114A(bool $forceReload = false)
    {
        if ($this->E8B5ABF3E946114A === null || $forceReload) {
            $this->E8B5ABF3E946114A = SyliusProvinceService::getInstance()->getEntityByPrimaryKey($this->provinceId);
        }
        return $this->E8B5ABF3E946114A;
    }

    /**
     * @param SyliusProvince $entity
     * 
     * @return void
     */
    public function setE8B5ABF3E946114A(SyliusProvince $entity = null)
    {
        $this->E8B5ABF3E946114A = $entity;
        $this->provinceId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCountry|null
     */
    public function getE8B5ABF3F92F3E70(bool $forceReload = false)
    {
        if ($this->E8B5ABF3F92F3E70 === null || $forceReload) {
            $this->E8B5ABF3F92F3E70 = SyliusCountryService::getInstance()->getEntityByPrimaryKey($this->countryId);
        }
        return $this->E8B5ABF3F92F3E70;
    }

    /**
     * @param SyliusCountry $entity
     * 
     * @return void
     */
    public function setE8B5ABF3F92F3E70(SyliusCountry $entity = null)
    {
        $this->E8B5ABF3F92F3E70 = $entity;
        $this->countryId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusZoneMember $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusZoneMember $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}