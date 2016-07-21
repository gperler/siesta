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

class sylius_customer implements ArraySerializable
{

    const TABLE_NAME = "sylius_customer";

    const COLUMN_ID = "id";

    const COLUMN_BILLING_ADDRESS_ID = "billing_address_id";

    const COLUMN_SHIPPING_ADDRESS_ID = "shipping_address_id";

    const COLUMN_EMAIL = "email";

    const COLUMN_EMAIL_CANONICAL = "email_canonical";

    const COLUMN_FIRST_NAME = "first_name";

    const COLUMN_LAST_NAME = "last_name";

    const COLUMN_BIRTHDAY = "birthday";

    const COLUMN_GENDER = "gender";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

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
    protected $billing_address_id;

    /**
     * @var int
     */
    protected $shipping_address_id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $email_canonical;

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

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
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var SiestaDateTime
     */
    protected $deleted_at;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var sylius_address
     */
    protected $7E82D5E64D4CFF2B;

    /**
     * @var sylius_address
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->billing_address_id) . ',' . Escaper::quoteInt($this->shipping_address_id) . ',' . Escaper::quoteString($connection, $this->email) . ',' . Escaper::quoteString($connection, $this->email_canonical) . ',' . Escaper::quoteString($connection, $this->first_name) . ',' . Escaper::quoteString($connection, $this->last_name) . ',' . Escaper::quoteDateTime($this->birthday) . ',' . Escaper::quoteString($connection, $this->gender) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ',' . Escaper::quoteString($connection, $this->currency) . ');';
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
        $this->billing_address_id = $resultSet->getIntegerValue("billing_address_id");
        $this->shipping_address_id = $resultSet->getIntegerValue("shipping_address_id");
        $this->email = $resultSet->getStringValue("email");
        $this->email_canonical = $resultSet->getStringValue("email_canonical");
        $this->first_name = $resultSet->getStringValue("first_name");
        $this->last_name = $resultSet->getStringValue("last_name");
        $this->birthday = $resultSet->getDateTime("birthday");
        $this->gender = $resultSet->getStringValue("gender");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
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
        $this->setBilling_address_id($arrayAccessor->getIntegerValue("billing_address_id"));
        $this->setShipping_address_id($arrayAccessor->getIntegerValue("shipping_address_id"));
        $this->setEmail($arrayAccessor->getStringValue("email"));
        $this->setEmail_canonical($arrayAccessor->getStringValue("email_canonical"));
        $this->setFirst_name($arrayAccessor->getStringValue("first_name"));
        $this->setLast_name($arrayAccessor->getStringValue("last_name"));
        $this->setBirthday($arrayAccessor->getDateTime("birthday"));
        $this->setGender($arrayAccessor->getStringValue("gender"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->setCurrency($arrayAccessor->getStringValue("currency"));
        $this->_existing = ($this->id !== null);
        $7E82D5E64D4CFF2BArray = $arrayAccessor->getArray("7E82D5E64D4CFF2B");
        if ($7E82D5E64D4CFF2BArray !== null) {
            $7E82D5E64D4CFF2B = sylius_addressService::getInstance()->newInstance();
            $7E82D5E64D4CFF2B->fromArray($7E82D5E64D4CFF2BArray);
            $this->set7E82D5E64D4CFF2B($7E82D5E64D4CFF2B);
        }
        $7E82D5E679D0C0E4Array = $arrayAccessor->getArray("7E82D5E679D0C0E4");
        if ($7E82D5E679D0C0E4Array !== null) {
            $7E82D5E679D0C0E4 = sylius_addressService::getInstance()->newInstance();
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
            "billing_address_id" => $this->getBilling_address_id(),
            "shipping_address_id" => $this->getShipping_address_id(),
            "email" => $this->getEmail(),
            "email_canonical" => $this->getEmail_canonical(),
            "first_name" => $this->getFirst_name(),
            "last_name" => $this->getLast_name(),
            "birthday" => ($this->getBirthday() !== null) ? $this->getBirthday()->getJSONDateTime() : null,
            "gender" => $this->getGender(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null,
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
    public function getBilling_address_id()
    {
        return $this->billing_address_id;
    }

    /**
     * @param int $billing_address_id
     * 
     * @return void
     */
    public function setBilling_address_id(int $billing_address_id = null)
    {
        $this->billing_address_id = $billing_address_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getShipping_address_id()
    {
        return $this->shipping_address_id;
    }

    /**
     * @param int $shipping_address_id
     * 
     * @return void
     */
    public function setShipping_address_id(int $shipping_address_id = null)
    {
        $this->shipping_address_id = $shipping_address_id;
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
    public function getEmail_canonical()
    {
        return $this->email_canonical;
    }

    /**
     * @param string $email_canonical
     * 
     * @return void
     */
    public function setEmail_canonical(string $email_canonical = null)
    {
        $this->email_canonical = StringUtil::trimToNull($email_canonical, 255);
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
     * 
     * @return SiestaDateTime|null
     */
    public function getDeleted_at()
    {
        return $this->deleted_at;
    }

    /**
     * @param SiestaDateTime $deleted_at
     * 
     * @return void
     */
    public function setDeleted_at(SiestaDateTime $deleted_at = null)
    {
        $this->deleted_at = $deleted_at;
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
     * @return sylius_address|null
     */
    public function get7E82D5E64D4CFF2B(bool $forceReload = false)
    {
        if ($this->7E82D5E64D4CFF2B === null || $forceReload) {
            $this->7E82D5E64D4CFF2B = sylius_addressService::getInstance()->getEntityByPrimaryKey($this->shipping_address_id);
        }
        return $this->7E82D5E64D4CFF2B;
    }

    /**
     * @param sylius_address $entity
     * 
     * @return void
     */
    public function set7E82D5E64D4CFF2B(sylius_address $entity = null)
    {
        $this->7E82D5E64D4CFF2B = $entity;
        $this->shipping_address_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_address|null
     */
    public function get7E82D5E679D0C0E4(bool $forceReload = false)
    {
        if ($this->7E82D5E679D0C0E4 === null || $forceReload) {
            $this->7E82D5E679D0C0E4 = sylius_addressService::getInstance()->getEntityByPrimaryKey($this->billing_address_id);
        }
        return $this->7E82D5E679D0C0E4;
    }

    /**
     * @param sylius_address $entity
     * 
     * @return void
     */
    public function set7E82D5E679D0C0E4(sylius_address $entity = null)
    {
        $this->7E82D5E679D0C0E4 = $entity;
        $this->billing_address_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_customer $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_customer $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}