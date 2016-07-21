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

class sylius_user implements ArraySerializable
{

    const TABLE_NAME = "sylius_user";

    const COLUMN_ID = "id";

    const COLUMN_CUSTOMER_ID = "customer_id";

    const COLUMN_USERNAME = "username";

    const COLUMN_USERNAME_CANONICAL = "username_canonical";

    const COLUMN_ENABLED = "enabled";

    const COLUMN_SALT = "salt";

    const COLUMN_PASSWORD = "password";

    const COLUMN_LAST_LOGIN = "last_login";

    const COLUMN_CONFIRMATION_TOKEN = "confirmation_token";

    const COLUMN_PASSWORD_REQUESTED_AT = "password_requested_at";

    const COLUMN_LOCKED = "locked";

    const COLUMN_EXPIRES_AT = "expires_at";

    const COLUMN_CREDENTIALS_EXPIRE_AT = "credentials_expire_at";

    const COLUMN_ROLES = "roles";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

    const COLUMN_DELETED_AT = "deleted_at";

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
    protected $customer_id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $username_canonical;

    /**
     * @var string
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var SiestaDateTime
     */
    protected $last_login;

    /**
     * @var string
     */
    protected $confirmation_token;

    /**
     * @var SiestaDateTime
     */
    protected $password_requested_at;

    /**
     * @var string
     */
    protected $locked;

    /**
     * @var SiestaDateTime
     */
    protected $expires_at;

    /**
     * @var SiestaDateTime
     */
    protected $credentials_expire_at;

    /**
     * @var string
     */
    protected $roles;

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
     * @var sylius_customer
     */
    protected $569A33C09395C3F3;

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
        $spCall = ($this->_existing) ? "CALL sylius_user_U(" : "CALL sylius_user_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->customer_id) . ',' . Escaper::quoteString($connection, $this->username) . ',' . Escaper::quoteString($connection, $this->username_canonical) . ',' . Escaper::quoteString($connection, $this->enabled) . ',' . Escaper::quoteString($connection, $this->salt) . ',' . Escaper::quoteString($connection, $this->password) . ',' . Escaper::quoteDateTime($this->last_login) . ',' . Escaper::quoteString($connection, $this->confirmation_token) . ',' . Escaper::quoteDateTime($this->password_requested_at) . ',' . Escaper::quoteString($connection, $this->locked) . ',' . Escaper::quoteDateTime($this->expires_at) . ',' . Escaper::quoteDateTime($this->credentials_expire_at) . ',' . Escaper::quoteString($connection, $this->roles) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ',' . Escaper::quoteDateTime($this->deleted_at) . ');';
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
        if ($cascade && $this->569A33C09395C3F3 !== null) {
            $this->569A33C09395C3F3->save($cascade, $cycleDetector, $connectionName);
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
        $this->customer_id = $resultSet->getIntegerValue("customer_id");
        $this->username = $resultSet->getStringValue("username");
        $this->username_canonical = $resultSet->getStringValue("username_canonical");
        $this->enabled = $resultSet->getStringValue("enabled");
        $this->salt = $resultSet->getStringValue("salt");
        $this->password = $resultSet->getStringValue("password");
        $this->last_login = $resultSet->getDateTime("last_login");
        $this->confirmation_token = $resultSet->getStringValue("confirmation_token");
        $this->password_requested_at = $resultSet->getDateTime("password_requested_at");
        $this->locked = $resultSet->getStringValue("locked");
        $this->expires_at = $resultSet->getDateTime("expires_at");
        $this->credentials_expire_at = $resultSet->getDateTime("credentials_expire_at");
        $this->roles = $resultSet->getStringValue("roles");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
        $this->deleted_at = $resultSet->getDateTime("deleted_at");
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
        $connection->execute("CALL sylius_user_DB_PK($id)");
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
        $this->setCustomer_id($arrayAccessor->getIntegerValue("customer_id"));
        $this->setUsername($arrayAccessor->getStringValue("username"));
        $this->setUsername_canonical($arrayAccessor->getStringValue("username_canonical"));
        $this->setEnabled($arrayAccessor->getStringValue("enabled"));
        $this->setSalt($arrayAccessor->getStringValue("salt"));
        $this->setPassword($arrayAccessor->getStringValue("password"));
        $this->setLast_login($arrayAccessor->getDateTime("last_login"));
        $this->setConfirmation_token($arrayAccessor->getStringValue("confirmation_token"));
        $this->setPassword_requested_at($arrayAccessor->getDateTime("password_requested_at"));
        $this->setLocked($arrayAccessor->getStringValue("locked"));
        $this->setExpires_at($arrayAccessor->getDateTime("expires_at"));
        $this->setCredentials_expire_at($arrayAccessor->getDateTime("credentials_expire_at"));
        $this->setRoles($arrayAccessor->getStringValue("roles"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->setDeleted_at($arrayAccessor->getDateTime("deleted_at"));
        $this->_existing = ($this->id !== null);
        $569A33C09395C3F3Array = $arrayAccessor->getArray("569A33C09395C3F3");
        if ($569A33C09395C3F3Array !== null) {
            $569A33C09395C3F3 = sylius_customerService::getInstance()->newInstance();
            $569A33C09395C3F3->fromArray($569A33C09395C3F3Array);
            $this->set569A33C09395C3F3($569A33C09395C3F3);
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
            "customer_id" => $this->getCustomer_id(),
            "username" => $this->getUsername(),
            "username_canonical" => $this->getUsername_canonical(),
            "enabled" => $this->getEnabled(),
            "salt" => $this->getSalt(),
            "password" => $this->getPassword(),
            "last_login" => ($this->getLast_login() !== null) ? $this->getLast_login()->getJSONDateTime() : null,
            "confirmation_token" => $this->getConfirmation_token(),
            "password_requested_at" => ($this->getPassword_requested_at() !== null) ? $this->getPassword_requested_at()->getJSONDateTime() : null,
            "locked" => $this->getLocked(),
            "expires_at" => ($this->getExpires_at() !== null) ? $this->getExpires_at()->getJSONDateTime() : null,
            "credentials_expire_at" => ($this->getCredentials_expire_at() !== null) ? $this->getCredentials_expire_at()->getJSONDateTime() : null,
            "roles" => $this->getRoles(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null,
            "deleted_at" => ($this->getDeleted_at() !== null) ? $this->getDeleted_at()->getJSONDateTime() : null
        ];
        if ($this->569A33C09395C3F3 !== null) {
            $result["569A33C09395C3F3"] = $this->569A33C09395C3F3->toArray($cycleDetector);
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * 
     * @return void
     */
    public function setUsername(string $username = null)
    {
        $this->username = StringUtil::trimToNull($username, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getUsername_canonical()
    {
        return $this->username_canonical;
    }

    /**
     * @param string $username_canonical
     * 
     * @return void
     */
    public function setUsername_canonical(string $username_canonical = null)
    {
        $this->username_canonical = StringUtil::trimToNull($username_canonical, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $enabled
     * 
     * @return void
     */
    public function setEnabled(string $enabled = null)
    {
        $this->enabled = StringUtil::trimToNull($enabled, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     * 
     * @return void
     */
    public function setSalt(string $salt = null)
    {
        $this->salt = StringUtil::trimToNull($salt, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * 
     * @return void
     */
    public function setPassword(string $password = null)
    {
        $this->password = StringUtil::trimToNull($password, 255);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getLast_login()
    {
        return $this->last_login;
    }

    /**
     * @param SiestaDateTime $last_login
     * 
     * @return void
     */
    public function setLast_login(SiestaDateTime $last_login = null)
    {
        $this->last_login = $last_login;
    }

    /**
     * 
     * @return string|null
     */
    public function getConfirmation_token()
    {
        return $this->confirmation_token;
    }

    /**
     * @param string $confirmation_token
     * 
     * @return void
     */
    public function setConfirmation_token(string $confirmation_token = null)
    {
        $this->confirmation_token = StringUtil::trimToNull($confirmation_token, 255);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getPassword_requested_at()
    {
        return $this->password_requested_at;
    }

    /**
     * @param SiestaDateTime $password_requested_at
     * 
     * @return void
     */
    public function setPassword_requested_at(SiestaDateTime $password_requested_at = null)
    {
        $this->password_requested_at = $password_requested_at;
    }

    /**
     * 
     * @return string|null
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @param string $locked
     * 
     * @return void
     */
    public function setLocked(string $locked = null)
    {
        $this->locked = StringUtil::trimToNull($locked, null);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getExpires_at()
    {
        return $this->expires_at;
    }

    /**
     * @param SiestaDateTime $expires_at
     * 
     * @return void
     */
    public function setExpires_at(SiestaDateTime $expires_at = null)
    {
        $this->expires_at = $expires_at;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCredentials_expire_at()
    {
        return $this->credentials_expire_at;
    }

    /**
     * @param SiestaDateTime $credentials_expire_at
     * 
     * @return void
     */
    public function setCredentials_expire_at(SiestaDateTime $credentials_expire_at = null)
    {
        $this->credentials_expire_at = $credentials_expire_at;
    }

    /**
     * 
     * @return string|null
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $roles
     * 
     * @return void
     */
    public function setRoles(string $roles = null)
    {
        $this->roles = StringUtil::trimToNull($roles, null);
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
     * @param bool $forceReload
     * 
     * @return sylius_customer|null
     */
    public function get569A33C09395C3F3(bool $forceReload = false)
    {
        if ($this->569A33C09395C3F3 === null || $forceReload) {
            $this->569A33C09395C3F3 = sylius_customerService::getInstance()->getEntityByPrimaryKey($this->customer_id);
        }
        return $this->569A33C09395C3F3;
    }

    /**
     * @param sylius_customer $entity
     * 
     * @return void
     */
    public function set569A33C09395C3F3(sylius_customer $entity = null)
    {
        $this->569A33C09395C3F3 = $entity;
        $this->customer_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_user $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_user $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}