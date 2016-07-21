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

class sylius_zone_member implements ArraySerializable
{

    const TABLE_NAME = "sylius_zone_member";

    const COLUMN_ID = "id";

    const COLUMN_BELONGS_TO = "belongs_to";

    const COLUMN_COUNTRY_ID = "country_id";

    const COLUMN_PROVINCE_ID = "province_id";

    const COLUMN_ZONE_ID = "zone_id";

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
    protected $belongs_to;

    /**
     * @var int
     */
    protected $country_id;

    /**
     * @var int
     */
    protected $province_id;

    /**
     * @var int
     */
    protected $zone_id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var sylius_zone
     */
    protected $E8B5ABF34B0E929B;

    /**
     * @var sylius_zone
     */
    protected $E8B5ABF39F2C3FAB;

    /**
     * @var sylius_province
     */
    protected $E8B5ABF3E946114A;

    /**
     * @var sylius_country
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->belongs_to) . ',' . Escaper::quoteInt($this->country_id) . ',' . Escaper::quoteInt($this->province_id) . ',' . Escaper::quoteInt($this->zone_id) . ',' . Escaper::quoteString($connection, $this->type) . ');';
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
        $this->belongs_to = $resultSet->getIntegerValue("belongs_to");
        $this->country_id = $resultSet->getIntegerValue("country_id");
        $this->province_id = $resultSet->getIntegerValue("province_id");
        $this->zone_id = $resultSet->getIntegerValue("zone_id");
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
        $this->setBelongs_to($arrayAccessor->getIntegerValue("belongs_to"));
        $this->setCountry_id($arrayAccessor->getIntegerValue("country_id"));
        $this->setProvince_id($arrayAccessor->getIntegerValue("province_id"));
        $this->setZone_id($arrayAccessor->getIntegerValue("zone_id"));
        $this->setType($arrayAccessor->getStringValue("type"));
        $this->_existing = ($this->id !== null);
        $E8B5ABF34B0E929BArray = $arrayAccessor->getArray("E8B5ABF34B0E929B");
        if ($E8B5ABF34B0E929BArray !== null) {
            $E8B5ABF34B0E929B = sylius_zoneService::getInstance()->newInstance();
            $E8B5ABF34B0E929B->fromArray($E8B5ABF34B0E929BArray);
            $this->setE8B5ABF34B0E929B($E8B5ABF34B0E929B);
        }
        $E8B5ABF39F2C3FABArray = $arrayAccessor->getArray("E8B5ABF39F2C3FAB");
        if ($E8B5ABF39F2C3FABArray !== null) {
            $E8B5ABF39F2C3FAB = sylius_zoneService::getInstance()->newInstance();
            $E8B5ABF39F2C3FAB->fromArray($E8B5ABF39F2C3FABArray);
            $this->setE8B5ABF39F2C3FAB($E8B5ABF39F2C3FAB);
        }
        $E8B5ABF3E946114AArray = $arrayAccessor->getArray("E8B5ABF3E946114A");
        if ($E8B5ABF3E946114AArray !== null) {
            $E8B5ABF3E946114A = sylius_provinceService::getInstance()->newInstance();
            $E8B5ABF3E946114A->fromArray($E8B5ABF3E946114AArray);
            $this->setE8B5ABF3E946114A($E8B5ABF3E946114A);
        }
        $E8B5ABF3F92F3E70Array = $arrayAccessor->getArray("E8B5ABF3F92F3E70");
        if ($E8B5ABF3F92F3E70Array !== null) {
            $E8B5ABF3F92F3E70 = sylius_countryService::getInstance()->newInstance();
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
            "belongs_to" => $this->getBelongs_to(),
            "country_id" => $this->getCountry_id(),
            "province_id" => $this->getProvince_id(),
            "zone_id" => $this->getZone_id(),
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
    public function getBelongs_to()
    {
        return $this->belongs_to;
    }

    /**
     * @param int $belongs_to
     * 
     * @return void
     */
    public function setBelongs_to(int $belongs_to = null)
    {
        $this->belongs_to = $belongs_to;
    }

    /**
     * 
     * @return int|null
     */
    public function getCountry_id()
    {
        return $this->country_id;
    }

    /**
     * @param int $country_id
     * 
     * @return void
     */
    public function setCountry_id(int $country_id = null)
    {
        $this->country_id = $country_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getProvince_id()
    {
        return $this->province_id;
    }

    /**
     * @param int $province_id
     * 
     * @return void
     */
    public function setProvince_id(int $province_id = null)
    {
        $this->province_id = $province_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getZone_id()
    {
        return $this->zone_id;
    }

    /**
     * @param int $zone_id
     * 
     * @return void
     */
    public function setZone_id(int $zone_id = null)
    {
        $this->zone_id = $zone_id;
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
     * @return sylius_zone|null
     */
    public function getE8B5ABF34B0E929B(bool $forceReload = false)
    {
        if ($this->E8B5ABF34B0E929B === null || $forceReload) {
            $this->E8B5ABF34B0E929B = sylius_zoneService::getInstance()->getEntityByPrimaryKey($this->belongs_to);
        }
        return $this->E8B5ABF34B0E929B;
    }

    /**
     * @param sylius_zone $entity
     * 
     * @return void
     */
    public function setE8B5ABF34B0E929B(sylius_zone $entity = null)
    {
        $this->E8B5ABF34B0E929B = $entity;
        $this->belongs_to = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_zone|null
     */
    public function getE8B5ABF39F2C3FAB(bool $forceReload = false)
    {
        if ($this->E8B5ABF39F2C3FAB === null || $forceReload) {
            $this->E8B5ABF39F2C3FAB = sylius_zoneService::getInstance()->getEntityByPrimaryKey($this->zone_id);
        }
        return $this->E8B5ABF39F2C3FAB;
    }

    /**
     * @param sylius_zone $entity
     * 
     * @return void
     */
    public function setE8B5ABF39F2C3FAB(sylius_zone $entity = null)
    {
        $this->E8B5ABF39F2C3FAB = $entity;
        $this->zone_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_province|null
     */
    public function getE8B5ABF3E946114A(bool $forceReload = false)
    {
        if ($this->E8B5ABF3E946114A === null || $forceReload) {
            $this->E8B5ABF3E946114A = sylius_provinceService::getInstance()->getEntityByPrimaryKey($this->province_id);
        }
        return $this->E8B5ABF3E946114A;
    }

    /**
     * @param sylius_province $entity
     * 
     * @return void
     */
    public function setE8B5ABF3E946114A(sylius_province $entity = null)
    {
        $this->E8B5ABF3E946114A = $entity;
        $this->province_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_country|null
     */
    public function getE8B5ABF3F92F3E70(bool $forceReload = false)
    {
        if ($this->E8B5ABF3F92F3E70 === null || $forceReload) {
            $this->E8B5ABF3F92F3E70 = sylius_countryService::getInstance()->getEntityByPrimaryKey($this->country_id);
        }
        return $this->E8B5ABF3F92F3E70;
    }

    /**
     * @param sylius_country $entity
     * 
     * @return void
     */
    public function setE8B5ABF3F92F3E70(sylius_country $entity = null)
    {
        $this->E8B5ABF3F92F3E70 = $entity;
        $this->country_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_zone_member $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_zone_member $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}