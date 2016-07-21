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

class SyliusCustomer implements ArraySerializable
{

    const TABLE_NAME = "sylius_customer";

    const COLUMN_ID = "id";

    const COLUMN_BILLINGADDRESSID = "billing_address_id";

    const COLUMN_SHIPPINGADDRESSID = "shipping_address_id";

    const COLUMN_EMAIL = "email";

    const COLUMN_EMAILCANONICAL = "email_canonical";

    const COLUMN_FIRSTNAME = "first_name";

    const COLUMN_LASTNAME = "last_name";

    const COLUMN_BIRTHDAY = "birthday";

    const COLUMN_GENDER = "gender";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

    const COLUMN_CURRENCY = "currency";

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
    protected $billingAddressId;

    /**
     * @var int
     */
    protected $shippingAddressId;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $emailCanonical;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var SiestaDateTime
     */
    protected $birthday;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SiestaDateTime
     */
    protected $deletedAt;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var SyliusAddress
     */
    protected $7E82D5E64D4CFF2B;

    /**
     * @var SyliusAddress
     */
    protected $7E82D5E679D0C0E4;

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
        $spCall = ($this->_existing) ? "CALL sylius_customer_U(" : "CALL sylius_customer_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->billingAddressId) . ',' . Escaper::quoteInt($this->shippingAddressId) . ',' . Escaper::quoteString($connection, $this->email) . ',' . Escaper::quoteString($connection, $this->emailCanonical) . ',' . Escaper::quoteString($connection, $this->firstName) . ',' . Escaper::quoteString($connection, $this->lastName) . ',' . Escaper::quoteDateTime($this->birthday) . ',' . Escaper::quoteString($connection, $this->gender) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ',' . Escaper::quoteString($connection, $this->currency) . ');';
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
        if ($cascade && $this->7E82D5E64D4CFF2B !== null) {
            $this->7E82D5E64D4CFF2B->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->7E82D5E679D0C0E4 !== null) {
            $this->7E82D5E679D0C0E4->save($cascade, $cycleDetector, $connectionName);
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
        $this->billingAddressId = $resultSet->getIntegerValue("billing_address_id");
        $this->shippingAddressId = $resultSet->getIntegerValue("shipping_address_id");
        $this->email = $resultSet->getStringValue("email");
        $this->emailCanonical = $resultSet->getStringValue("email_canonical");
        $this->firstName = $resultSet->getStringValue("first_name");
        $this->lastName = $resultSet->getStringValue("last_name");
        $this->birthday = $resultSet->getDateTime("birthday");
        $this->gender = $resultSet->getStringValue("gender");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
        $this->currency = $resultSet->getStringValue("currency");
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
        $connection->execute("CALL sylius_customer_DB_PK($id)");
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
        $this->setBillingAddressId($arrayAccessor->getIntegerValue("billingAddressId"));
        $this->setShippingAddressId($arrayAccessor->getIntegerValue("shippingAddressId"));
        $this->setEmail($arrayAccessor->getStringValue("email"));
        $this->setEmailCanonical($arrayAccessor->getStringValue("emailCanonical"));
        $this->setFirstName($arrayAccessor->getStringValue("firstName"));
        $this->setLastName($arrayAccessor->getStringValue("lastName"));
        $this->setBirthday($arrayAccessor->getDateTime("birthday"));
        $this->setGender($arrayAccessor->getStringValue("gender"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->setCurrency($arrayAccessor->getStringValue("currency"));
        $this->_existing = ($this->id !== null);
        $7E82D5E64D4CFF2BArray = $arrayAccessor->getArray("7E82D5E64D4CFF2B");
        if ($7E82D5E64D4CFF2BArray !== null) {
            $7E82D5E64D4CFF2B = SyliusAddressService::getInstance()->newInstance();
            $7E82D5E64D4CFF2B->fromArray($7E82D5E64D4CFF2BArray);
            $this->set7E82D5E64D4CFF2B($7E82D5E64D4CFF2B);
        }
        $7E82D5E679D0C0E4Array = $arrayAccessor->getArray("7E82D5E679D0C0E4");
        if ($7E82D5E679D0C0E4Array !== null) {
            $7E82D5E679D0C0E4 = SyliusAddressService::getInstance()->newInstance();
            $7E82D5E679D0C0E4->fromArray($7E82D5E679D0C0E4Array);
            $this->set7E82D5E679D0C0E4($7E82D5E679D0C0E4);
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
            "billingAddressId" => $this->getBillingAddressId(),
            "shippingAddressId" => $this->getShippingAddressId(),
            "email" => $this->getEmail(),
            "emailCanonical" => $this->getEmailCanonical(),
            "firstName" => $this->getFirstName(),
            "lastName" => $this->getLastName(),
            "birthday" => ($this->getBirthday() !== null) ? $this->getBirthday()->getJSONDateTime() : null,
            "gender" => $this->getGender(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null,
            "currency" => $this->getCurrency()
        ];
        if ($this->7E82D5E64D4CFF2B !== null) {
            $result["7E82D5E64D4CFF2B"] = $this->7E82D5E64D4CFF2B->toArray($cycleDetector);
        }
        if ($this->7E82D5E679D0C0E4 !== null) {
            $result["7E82D5E679D0C0E4"] = $this->7E82D5E679D0C0E4->toArray($cycleDetector);
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
    public function getBillingAddressId()
    {
        return $this->billingAddressId;
    }

    /**
     * @param int $billingAddressId
     * 
     * @return void
     */
    public function setBillingAddressId(int $billingAddressId = null)
    {
        $this->billingAddressId = $billingAddressId;
    }

    /**
     * 
     * @return int|null
     */
    public function getShippingAddressId()
    {
        return $this->shippingAddressId;
    }

    /**
     * @param int $shippingAddressId
     * 
     * @return void
     */
    public function setShippingAddressId(int $shippingAddressId = null)
    {
        $this->shippingAddressId = $shippingAddressId;
    }

    /**
     * 
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * 
     * @return void
     */
    public function setEmail(string $email = null)
    {
        $this->email = StringUtil::trimToNull($email, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * @param string $emailCanonical
     * 
     * @return void
     */
    public function setEmailCanonical(string $emailCanonical = null)
    {
        $this->emailCanonical = StringUtil::trimToNull($emailCanonical, 255);
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
     * @return SiestaDateTime|null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param SiestaDateTime $birthday
     * 
     * @return void
     */
    public function setBirthday(SiestaDateTime $birthday = null)
    {
        $this->birthday = $birthday;
    }

    /**
     * 
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * 
     * @return void
     */
    public function setGender(string $gender = null)
    {
        $this->gender = StringUtil::trimToNull($gender, 1);
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
     * 
     * @return SiestaDateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param SiestaDateTime $deletedAt
     * 
     * @return void
     */
    public function setDeletedAt(SiestaDateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * 
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * 
     * @return void
     */
    public function setCurrency(string $currency = null)
    {
        $this->currency = StringUtil::trimToNull($currency, 3);
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusAddress|null
     */
    public function get7E82D5E64D4CFF2B(bool $forceReload = false)
    {
        if ($this->7E82D5E64D4CFF2B === null || $forceReload) {
            $this->7E82D5E64D4CFF2B = SyliusAddressService::getInstance()->getEntityByPrimaryKey($this->shippingAddressId);
        }
        return $this->7E82D5E64D4CFF2B;
    }

    /**
     * @param SyliusAddress $entity
     * 
     * @return void
     */
    public function set7E82D5E64D4CFF2B(SyliusAddress $entity = null)
    {
        $this->7E82D5E64D4CFF2B = $entity;
        $this->shippingAddressId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusAddress|null
     */
    public function get7E82D5E679D0C0E4(bool $forceReload = false)
    {
        if ($this->7E82D5E679D0C0E4 === null || $forceReload) {
            $this->7E82D5E679D0C0E4 = SyliusAddressService::getInstance()->getEntityByPrimaryKey($this->billingAddressId);
        }
        return $this->7E82D5E679D0C0E4;
    }

    /**
     * @param SyliusAddress $entity
     * 
     * @return void
     */
    public function set7E82D5E679D0C0E4(SyliusAddress $entity = null)
    {
        $this->7E82D5E679D0C0E4 = $entity;
        $this->billingAddressId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusCustomer $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusCustomer $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}