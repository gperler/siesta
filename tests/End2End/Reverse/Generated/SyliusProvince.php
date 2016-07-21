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

class SyliusProvince implements ArraySerializable
{

    const TABLE_NAME = "sylius_province";

    const COLUMN_ID = "id";

    const COLUMN_COUNTRYID = "country_id";

    const COLUMN_NAME = "name";

    const COLUMN_ISONAME = "iso_name";

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
    protected $countryId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $isoName;

    /**
     * @var SyliusCountry
     */
    protected $B5618FE4F92F3E70;

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
        $spCall = ($this->_existing) ? "CALL sylius_province_U(" : "CALL sylius_province_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->countryId) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->isoName) . ');';
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
        if ($cascade && $this->B5618FE4F92F3E70 !== null) {
            $this->B5618FE4F92F3E70->save($cascade, $cycleDetector, $connectionName);
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
        $this->countryId = $resultSet->getIntegerValue("country_id");
        $this->name = $resultSet->getStringValue("name");
        $this->isoName = $resultSet->getStringValue("iso_name");
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
        $connection->execute("CALL sylius_province_DB_PK($id)");
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
        $this->setCountryId($arrayAccessor->getIntegerValue("countryId"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setIsoName($arrayAccessor->getStringValue("isoName"));
        $this->_existing = ($this->id !== null);
        $B5618FE4F92F3E70Array = $arrayAccessor->getArray("B5618FE4F92F3E70");
        if ($B5618FE4F92F3E70Array !== null) {
            $B5618FE4F92F3E70 = SyliusCountryService::getInstance()->newInstance();
            $B5618FE4F92F3E70->fromArray($B5618FE4F92F3E70Array);
            $this->setB5618FE4F92F3E70($B5618FE4F92F3E70);
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
            "countryId" => $this->getCountryId(),
            "name" => $this->getName(),
            "isoName" => $this->getIsoName()
        ];
        if ($this->B5618FE4F92F3E70 !== null) {
            $result["B5618FE4F92F3E70"] = $this->B5618FE4F92F3E70->toArray($cycleDetector);
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
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name = null)
    {
        $this->name = StringUtil::trimToNull($name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getIsoName()
    {
        return $this->isoName;
    }

    /**
     * @param string $isoName
     * 
     * @return void
     */
    public function setIsoName(string $isoName = null)
    {
        $this->isoName = StringUtil::trimToNull($isoName, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCountry|null
     */
    public function getB5618FE4F92F3E70(bool $forceReload = false)
    {
        if ($this->B5618FE4F92F3E70 === null || $forceReload) {
            $this->B5618FE4F92F3E70 = SyliusCountryService::getInstance()->getEntityByPrimaryKey($this->countryId);
        }
        return $this->B5618FE4F92F3E70;
    }

    /**
     * @param SyliusCountry $entity
     * 
     * @return void
     */
    public function setB5618FE4F92F3E70(SyliusCountry $entity = null)
    {
        $this->B5618FE4F92F3E70 = $entity;
        $this->countryId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProvince $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProvince $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}