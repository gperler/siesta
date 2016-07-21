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

class sylius_province implements ArraySerializable
{

    const TABLE_NAME = "sylius_province";

    const COLUMN_ID = "id";

    const COLUMN_COUNTRY_ID = "country_id";

    const COLUMN_NAME = "name";

    const COLUMN_ISO_NAME = "iso_name";

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
    protected $country_id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $iso_name;

    /**
     * @var sylius_country
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->country_id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->iso_name) . ');';
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
        $this->country_id = $resultSet->getIntegerValue("country_id");
        $this->name = $resultSet->getStringValue("name");
        $this->iso_name = $resultSet->getStringValue("iso_name");
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
        $this->setCountry_id($arrayAccessor->getIntegerValue("country_id"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setIso_name($arrayAccessor->getStringValue("iso_name"));
        $this->_existing = ($this->id !== null);
        $B5618FE4F92F3E70Array = $arrayAccessor->getArray("B5618FE4F92F3E70");
        if ($B5618FE4F92F3E70Array !== null) {
            $B5618FE4F92F3E70 = sylius_countryService::getInstance()->newInstance();
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
            "country_id" => $this->getCountry_id(),
            "name" => $this->getName(),
            "iso_name" => $this->getIso_name()
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
    public function getIso_name()
    {
        return $this->iso_name;
    }

    /**
     * @param string $iso_name
     * 
     * @return void
     */
    public function setIso_name(string $iso_name = null)
    {
        $this->iso_name = StringUtil::trimToNull($iso_name, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_country|null
     */
    public function getB5618FE4F92F3E70(bool $forceReload = false)
    {
        if ($this->B5618FE4F92F3E70 === null || $forceReload) {
            $this->B5618FE4F92F3E70 = sylius_countryService::getInstance()->getEntityByPrimaryKey($this->country_id);
        }
        return $this->B5618FE4F92F3E70;
    }

    /**
     * @param sylius_country $entity
     * 
     * @return void
     */
    public function setB5618FE4F92F3E70(sylius_country $entity = null)
    {
        $this->B5618FE4F92F3E70 = $entity;
        $this->country_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_province $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_province $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}