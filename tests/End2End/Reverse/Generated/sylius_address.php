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

class sylius_address implements ArraySerializable
{

    const TABLE_NAME = "sylius_address";

    const COLUMN_ID = "id";

    const COLUMN_COUNTRY_ID = "country_id";

    const COLUMN_PROVINCE_ID = "province_id";

    const COLUMN_CUSTOMER_ID = "customer_id";

    const COLUMN_FIRST_NAME = "first_name";

    const COLUMN_LAST_NAME = "last_name";

    const COLUMN_PHONE_NUMBER = "phone_number";

    const COLUMN_STREET = "street";

    const COLUMN_COMPANY = "company";

    const COLUMN_CITY = "city";

    const COLUMN_POSTCODE = "postcode";

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
    protected $customer_id;

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $phone_number;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $postcode;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_customer
     */
    protected $B97FF0589395C3F3;

    /**
     * @var sylius_province
     */
    protected $B97FF058E946114A;

    /**
     * @var sylius_country
     */
    protected $B97FF058F92F3E70;

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
        $spCall = ($this->_existing) ? "CALL sylius_address_U(" : "CALL sylius_address_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->country_id) . ',' . Escaper::quoteInt($this->province_id) . ',' . Escaper::quoteInt($this->customer_id) . ',' . Escaper::quoteString($connection, $this->first_name) . ',' . Escaper::quoteString($connection, $this->last_name) . ',' . Escaper::quoteString($connection, $this->phone_number) . ',' . Escaper::quoteString($connection, $this->street) . ',' . Escaper::quoteString($connection, $this->company) . ',' . Escaper::quoteString($connection, $this->city) . ',' . Escaper::quoteString($connection, $this->postcode) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        if ($cascade && $this->B97FF0589395C3F3 !== null) {
            $this->B97FF0589395C3F3->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->B97FF058E946114A !== null) {
            $this->B97FF058E946114A->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->B97FF058F92F3E70 !== null) {
            $this->B97FF058F92F3E70->save($cascade, $cycleDetector, $connectionName);
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
        $this->province_id = $resultSet->getIntegerValue("province_id");
        $this->customer_id = $resultSet->getIntegerValue("customer_id");
        $this->first_name = $resultSet->getStringValue("first_name");
        $this->last_name = $resultSet->getStringValue("last_name");
        $this->phone_number = $resultSet->getStringValue("phone_number");
        $this->street = $resultSet->getStringValue("street");
        $this->company = $resultSet->getStringValue("company");
        $this->city = $resultSet->getStringValue("city");
        $this->postcode = $resultSet->getStringValue("postcode");
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
        $connection->execute("CALL sylius_address_DB_PK($id)");
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
        $this->setProvince_id($arrayAccessor->getIntegerValue("province_id"));
        $this->setCustomer_id($arrayAccessor->getIntegerValue("customer_id"));
        $this->setFirst_name($arrayAccessor->getStringValue("first_name"));
        $this->setLast_name($arrayAccessor->getStringValue("last_name"));
        $this->setPhone_number($arrayAccessor->getStringValue("phone_number"));
        $this->setStreet($arrayAccessor->getStringValue("street"));
        $this->setCompany($arrayAccessor->getStringValue("company"));
        $this->setCity($arrayAccessor->getStringValue("city"));
        $this->setPostcode($arrayAccessor->getStringValue("postcode"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $B97FF0589395C3F3Array = $arrayAccessor->getArray("B97FF0589395C3F3");
        if ($B97FF0589395C3F3Array !== null) {
            $B97FF0589395C3F3 = sylius_customerService::getInstance()->newInstance();
            $B97FF0589395C3F3->fromArray($B97FF0589395C3F3Array);
            $this->setB97FF0589395C3F3($B97FF0589395C3F3);
        }
        $B97FF058E946114AArray = $arrayAccessor->getArray("B97FF058E946114A");
        if ($B97FF058E946114AArray !== null) {
            $B97FF058E946114A = sylius_provinceService::getInstance()->newInstance();
            $B97FF058E946114A->fromArray($B97FF058E946114AArray);
            $this->setB97FF058E946114A($B97FF058E946114A);
        }
        $B97FF058F92F3E70Array = $arrayAccessor->getArray("B97FF058F92F3E70");
        if ($B97FF058F92F3E70Array !== null) {
            $B97FF058F92F3E70 = sylius_countryService::getInstance()->newInstance();
            $B97FF058F92F3E70->fromArray($B97FF058F92F3E70Array);
            $this->setB97FF058F92F3E70($B97FF058F92F3E70);
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
            "province_id" => $this->getProvince_id(),
            "customer_id" => $this->getCustomer_id(),
            "first_name" => $this->getFirst_name(),
            "last_name" => $this->getLast_name(),
            "phone_number" => $this->getPhone_number(),
            "street" => $this->getStreet(),
            "company" => $this->getCompany(),
            "city" => $this->getCity(),
            "postcode" => $this->getPostcode(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
        ];
        if ($this->B97FF0589395C3F3 !== null) {
            $result["B97FF0589395C3F3"] = $this->B97FF0589395C3F3->toArray($cycleDetector);
        }
        if ($this->B97FF058E946114A !== null) {
            $result["B97FF058E946114A"] = $this->B97FF058E946114A->toArray($cycleDetector);
        }
        if ($this->B97FF058F92F3E70 !== null) {
            $result["B97FF058F92F3E70"] = $this->B97FF058F92F3E70->toArray($cycleDetector);
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
    public function getCustomer_id()
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * 
     * @return void
     */
    public function setCustomer_id(int $customer_id = null)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * 
     * @return void
     */
    public function setFirst_name(string $first_name = null)
    {
        $this->first_name = StringUtil::trimToNull($first_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLast_name()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * 
     * @return void
     */
    public function setLast_name(string $last_name = null)
    {
        $this->last_name = StringUtil::trimToNull($last_name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPhone_number()
    {
        return $this->phone_number;
    }

    /**
     * @param string $phone_number
     * 
     * @return void
     */
    public function setPhone_number(string $phone_number = null)
    {
        $this->phone_number = StringUtil::trimToNull($phone_number, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * 
     * @return void
     */
    public function setStreet(string $street = null)
    {
        $this->street = StringUtil::trimToNull($street, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     * 
     * @return void
     */
    public function setCompany(string $company = null)
    {
        $this->company = StringUtil::trimToNull($company, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * 
     * @return void
     */
    public function setCity(string $city = null)
    {
        $this->city = StringUtil::trimToNull($city, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * 
     * @return void
     */
    public function setPostcode(string $postcode = null)
    {
        $this->postcode = StringUtil::trimToNull($postcode, 255);
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
     * @param bool $forceReload
     * 
     * @return sylius_customer|null
     */
    public function getB97FF0589395C3F3(bool $forceReload = false)
    {
        if ($this->B97FF0589395C3F3 === null || $forceReload) {
            $this->B97FF0589395C3F3 = sylius_customerService::getInstance()->getEntityByPrimaryKey($this->customer_id);
        }
        return $this->B97FF0589395C3F3;
    }

    /**
     * @param sylius_customer $entity
     * 
     * @return void
     */
    public function setB97FF0589395C3F3(sylius_customer $entity = null)
    {
        $this->B97FF0589395C3F3 = $entity;
        $this->customer_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_province|null
     */
    public function getB97FF058E946114A(bool $forceReload = false)
    {
        if ($this->B97FF058E946114A === null || $forceReload) {
            $this->B97FF058E946114A = sylius_provinceService::getInstance()->getEntityByPrimaryKey($this->province_id);
        }
        return $this->B97FF058E946114A;
    }

    /**
     * @param sylius_province $entity
     * 
     * @return void
     */
    public function setB97FF058E946114A(sylius_province $entity = null)
    {
        $this->B97FF058E946114A = $entity;
        $this->province_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_country|null
     */
    public function getB97FF058F92F3E70(bool $forceReload = false)
    {
        if ($this->B97FF058F92F3E70 === null || $forceReload) {
            $this->B97FF058F92F3E70 = sylius_countryService::getInstance()->getEntityByPrimaryKey($this->country_id);
        }
        return $this->B97FF058F92F3E70;
    }

    /**
     * @param sylius_country $entity
     * 
     * @return void
     */
    public function setB97FF058F92F3E70(sylius_country $entity = null)
    {
        $this->B97FF058F92F3E70 = $entity;
        $this->country_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_address $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_address $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}