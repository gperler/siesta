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

class SyliusAddress implements ArraySerializable
{

    const TABLE_NAME = "sylius_address";

    const COLUMN_ID = "id";

    const COLUMN_COUNTRYID = "country_id";

    const COLUMN_PROVINCEID = "province_id";

    const COLUMN_CUSTOMERID = "customer_id";

    const COLUMN_FIRSTNAME = "first_name";

    const COLUMN_LASTNAME = "last_name";

    const COLUMN_PHONENUMBER = "phone_number";

    const COLUMN_STREET = "street";

    const COLUMN_COMPANY = "company";

    const COLUMN_CITY = "city";

    const COLUMN_POSTCODE = "postcode";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

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
     * @var int
     */
    protected $provinceId;

    /**
     * @var int
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $phoneNumber;

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
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusCustomer
     */
    protected $B97FF0589395C3F3;

    /**
     * @var SyliusProvince
     */
    protected $B97FF058E946114A;

    /**
     * @var SyliusCountry
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->countryId) . ',' . Escaper::quoteInt($this->provinceId) . ',' . Escaper::quoteInt($this->customerId) . ',' . Escaper::quoteString($connection, $this->firstName) . ',' . Escaper::quoteString($connection, $this->lastName) . ',' . Escaper::quoteString($connection, $this->phoneNumber) . ',' . Escaper::quoteString($connection, $this->street) . ',' . Escaper::quoteString($connection, $this->company) . ',' . Escaper::quoteString($connection, $this->city) . ',' . Escaper::quoteString($connection, $this->postcode) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        $this->countryId = $resultSet->getIntegerValue("country_id");
        $this->provinceId = $resultSet->getIntegerValue("province_id");
        $this->customerId = $resultSet->getIntegerValue("customer_id");
        $this->firstName = $resultSet->getStringValue("first_name");
        $this->lastName = $resultSet->getStringValue("last_name");
        $this->phoneNumber = $resultSet->getStringValue("phone_number");
        $this->street = $resultSet->getStringValue("street");
        $this->company = $resultSet->getStringValue("company");
        $this->city = $resultSet->getStringValue("city");
        $this->postcode = $resultSet->getStringValue("postcode");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
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
        $this->setCountryId($arrayAccessor->getIntegerValue("countryId"));
        $this->setProvinceId($arrayAccessor->getIntegerValue("provinceId"));
        $this->setCustomerId($arrayAccessor->getIntegerValue("customerId"));
        $this->setFirstName($arrayAccessor->getStringValue("firstName"));
        $this->setLastName($arrayAccessor->getStringValue("lastName"));
        $this->setPhoneNumber($arrayAccessor->getStringValue("phoneNumber"));
        $this->setStreet($arrayAccessor->getStringValue("street"));
        $this->setCompany($arrayAccessor->getStringValue("company"));
        $this->setCity($arrayAccessor->getStringValue("city"));
        $this->setPostcode($arrayAccessor->getStringValue("postcode"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $B97FF0589395C3F3Array = $arrayAccessor->getArray("B97FF0589395C3F3");
        if ($B97FF0589395C3F3Array !== null) {
            $B97FF0589395C3F3 = SyliusCustomerService::getInstance()->newInstance();
            $B97FF0589395C3F3->fromArray($B97FF0589395C3F3Array);
            $this->setB97FF0589395C3F3($B97FF0589395C3F3);
        }
        $B97FF058E946114AArray = $arrayAccessor->getArray("B97FF058E946114A");
        if ($B97FF058E946114AArray !== null) {
            $B97FF058E946114A = SyliusProvinceService::getInstance()->newInstance();
            $B97FF058E946114A->fromArray($B97FF058E946114AArray);
            $this->setB97FF058E946114A($B97FF058E946114A);
        }
        $B97FF058F92F3E70Array = $arrayAccessor->getArray("B97FF058F92F3E70");
        if ($B97FF058F92F3E70Array !== null) {
            $B97FF058F92F3E70 = SyliusCountryService::getInstance()->newInstance();
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
            "countryId" => $this->getCountryId(),
            "provinceId" => $this->getProvinceId(),
            "customerId" => $this->getCustomerId(),
            "firstName" => $this->getFirstName(),
            "lastName" => $this->getLastName(),
            "phoneNumber" => $this->getPhoneNumber(),
            "street" => $this->getStreet(),
            "company" => $this->getCompany(),
            "city" => $this->getCity(),
            "postcode" => $this->getPostcode(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
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
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     * 
     * @return void
     */
    public function setCustomerId(int $customerId = null)
    {
        $this->customerId = $customerId;
    }

    /**
     * 
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * 
     * @return void
     */
    public function setFirstName(string $firstName = null)
    {
        $this->firstName = StringUtil::trimToNull($firstName, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * 
     * @return void
     */
    public function setLastName(string $lastName = null)
    {
        $this->lastName = StringUtil::trimToNull($lastName, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * 
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber = null)
    {
        $this->phoneNumber = StringUtil::trimToNull($phoneNumber, 255);
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param SiestaDateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(SiestaDateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param SiestaDateTime $updatedAt
     * 
     * @return void
     */
    public function setUpdatedAt(SiestaDateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCustomer|null
     */
    public function getB97FF0589395C3F3(bool $forceReload = false)
    {
        if ($this->B97FF0589395C3F3 === null || $forceReload) {
            $this->B97FF0589395C3F3 = SyliusCustomerService::getInstance()->getEntityByPrimaryKey($this->customerId);
        }
        return $this->B97FF0589395C3F3;
    }

    /**
     * @param SyliusCustomer $entity
     * 
     * @return void
     */
    public function setB97FF0589395C3F3(SyliusCustomer $entity = null)
    {
        $this->B97FF0589395C3F3 = $entity;
        $this->customerId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProvince|null
     */
    public function getB97FF058E946114A(bool $forceReload = false)
    {
        if ($this->B97FF058E946114A === null || $forceReload) {
            $this->B97FF058E946114A = SyliusProvinceService::getInstance()->getEntityByPrimaryKey($this->provinceId);
        }
        return $this->B97FF058E946114A;
    }

    /**
     * @param SyliusProvince $entity
     * 
     * @return void
     */
    public function setB97FF058E946114A(SyliusProvince $entity = null)
    {
        $this->B97FF058E946114A = $entity;
        $this->provinceId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCountry|null
     */
    public function getB97FF058F92F3E70(bool $forceReload = false)
    {
        if ($this->B97FF058F92F3E70 === null || $forceReload) {
            $this->B97FF058F92F3E70 = SyliusCountryService::getInstance()->getEntityByPrimaryKey($this->countryId);
        }
        return $this->B97FF058F92F3E70;
    }

    /**
     * @param SyliusCountry $entity
     * 
     * @return void
     */
    public function setB97FF058F92F3E70(SyliusCountry $entity = null)
    {
        $this->B97FF058F92F3E70 = $entity;
        $this->countryId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusAddress $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusAddress $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}