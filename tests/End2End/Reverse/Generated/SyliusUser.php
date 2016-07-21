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

class SyliusUser implements ArraySerializable
{

    const TABLE_NAME = "sylius_user";

    const COLUMN_ID = "id";

    const COLUMN_CUSTOMERID = "customer_id";

    const COLUMN_USERNAME = "username";

    const COLUMN_USERNAMECANONICAL = "username_canonical";

    const COLUMN_ENABLED = "enabled";

    const COLUMN_SALT = "salt";

    const COLUMN_PASSWORD = "password";

    const COLUMN_LASTLOGIN = "last_login";

    const COLUMN_CONFIRMATIONTOKEN = "confirmation_token";

    const COLUMN_PASSWORDREQUESTEDAT = "password_requested_at";

    const COLUMN_LOCKED = "locked";

    const COLUMN_EXPIRESAT = "expires_at";

    const COLUMN_CREDENTIALSEXPIREAT = "credentials_expire_at";

    const COLUMN_ROLES = "roles";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

    const COLUMN_DELETEDAT = "deleted_at";

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
    protected $customerId;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $usernameCanonical;

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
    protected $lastLogin;

    /**
     * @var string
     */
    protected $confirmationToken;

    /**
     * @var SiestaDateTime
     */
    protected $passwordRequestedAt;

    /**
     * @var string
     */
    protected $locked;

    /**
     * @var SiestaDateTime
     */
    protected $expiresAt;

    /**
     * @var SiestaDateTime
     */
    protected $credentialsExpireAt;

    /**
     * @var string
     */
    protected $roles;

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
     * @var SyliusCustomer
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->customerId) . ',' . Escaper::quoteString($connection, $this->username) . ',' . Escaper::quoteString($connection, $this->usernameCanonical) . ',' . Escaper::quoteString($connection, $this->enabled) . ',' . Escaper::quoteString($connection, $this->salt) . ',' . Escaper::quoteString($connection, $this->password) . ',' . Escaper::quoteDateTime($this->lastLogin) . ',' . Escaper::quoteString($connection, $this->confirmationToken) . ',' . Escaper::quoteDateTime($this->passwordRequestedAt) . ',' . Escaper::quoteString($connection, $this->locked) . ',' . Escaper::quoteDateTime($this->expiresAt) . ',' . Escaper::quoteDateTime($this->credentialsExpireAt) . ',' . Escaper::quoteString($connection, $this->roles) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ',' . Escaper::quoteDateTime($this->deletedAt) . ');';
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
        $this->customerId = $resultSet->getIntegerValue("customer_id");
        $this->username = $resultSet->getStringValue("username");
        $this->usernameCanonical = $resultSet->getStringValue("username_canonical");
        $this->enabled = $resultSet->getStringValue("enabled");
        $this->salt = $resultSet->getStringValue("salt");
        $this->password = $resultSet->getStringValue("password");
        $this->lastLogin = $resultSet->getDateTime("last_login");
        $this->confirmationToken = $resultSet->getStringValue("confirmation_token");
        $this->passwordRequestedAt = $resultSet->getDateTime("password_requested_at");
        $this->locked = $resultSet->getStringValue("locked");
        $this->expiresAt = $resultSet->getDateTime("expires_at");
        $this->credentialsExpireAt = $resultSet->getDateTime("credentials_expire_at");
        $this->roles = $resultSet->getStringValue("roles");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
        $this->deletedAt = $resultSet->getDateTime("deleted_at");
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
        $this->setCustomerId($arrayAccessor->getIntegerValue("customerId"));
        $this->setUsername($arrayAccessor->getStringValue("username"));
        $this->setUsernameCanonical($arrayAccessor->getStringValue("usernameCanonical"));
        $this->setEnabled($arrayAccessor->getStringValue("enabled"));
        $this->setSalt($arrayAccessor->getStringValue("salt"));
        $this->setPassword($arrayAccessor->getStringValue("password"));
        $this->setLastLogin($arrayAccessor->getDateTime("lastLogin"));
        $this->setConfirmationToken($arrayAccessor->getStringValue("confirmationToken"));
        $this->setPasswordRequestedAt($arrayAccessor->getDateTime("passwordRequestedAt"));
        $this->setLocked($arrayAccessor->getStringValue("locked"));
        $this->setExpiresAt($arrayAccessor->getDateTime("expiresAt"));
        $this->setCredentialsExpireAt($arrayAccessor->getDateTime("credentialsExpireAt"));
        $this->setRoles($arrayAccessor->getStringValue("roles"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->setDeletedAt($arrayAccessor->getDateTime("deletedAt"));
        $this->_existing = ($this->id !== null);
        $569A33C09395C3F3Array = $arrayAccessor->getArray("569A33C09395C3F3");
        if ($569A33C09395C3F3Array !== null) {
            $569A33C09395C3F3 = SyliusCustomerService::getInstance()->newInstance();
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
            "customerId" => $this->getCustomerId(),
            "username" => $this->getUsername(),
            "usernameCanonical" => $this->getUsernameCanonical(),
            "enabled" => $this->getEnabled(),
            "salt" => $this->getSalt(),
            "password" => $this->getPassword(),
            "lastLogin" => ($this->getLastLogin() !== null) ? $this->getLastLogin()->getJSONDateTime() : null,
            "confirmationToken" => $this->getConfirmationToken(),
            "passwordRequestedAt" => ($this->getPasswordRequestedAt() !== null) ? $this->getPasswordRequestedAt()->getJSONDateTime() : null,
            "locked" => $this->getLocked(),
            "expiresAt" => ($this->getExpiresAt() !== null) ? $this->getExpiresAt()->getJSONDateTime() : null,
            "credentialsExpireAt" => ($this->getCredentialsExpireAt() !== null) ? $this->getCredentialsExpireAt()->getJSONDateTime() : null,
            "roles" => $this->getRoles(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null,
            "deletedAt" => ($this->getDeletedAt() !== null) ? $this->getDeletedAt()->getJSONDateTime() : null
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
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    /**
     * @param string $usernameCanonical
     * 
     * @return void
     */
    public function setUsernameCanonical(string $usernameCanonical = null)
    {
        $this->usernameCanonical = StringUtil::trimToNull($usernameCanonical, 255);
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
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param SiestaDateTime $lastLogin
     * 
     * @return void
     */
    public function setLastLogin(SiestaDateTime $lastLogin = null)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * 
     * @return string|null
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param string $confirmationToken
     * 
     * @return void
     */
    public function setConfirmationToken(string $confirmationToken = null)
    {
        $this->confirmationToken = StringUtil::trimToNull($confirmationToken, 255);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param SiestaDateTime $passwordRequestedAt
     * 
     * @return void
     */
    public function setPasswordRequestedAt(SiestaDateTime $passwordRequestedAt = null)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
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
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param SiestaDateTime $expiresAt
     * 
     * @return void
     */
    public function setExpiresAt(SiestaDateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * @param SiestaDateTime $credentialsExpireAt
     * 
     * @return void
     */
    public function setCredentialsExpireAt(SiestaDateTime $credentialsExpireAt = null)
    {
        $this->credentialsExpireAt = $credentialsExpireAt;
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
     * @param bool $forceReload
     * 
     * @return SyliusCustomer|null
     */
    public function get569A33C09395C3F3(bool $forceReload = false)
    {
        if ($this->569A33C09395C3F3 === null || $forceReload) {
            $this->569A33C09395C3F3 = SyliusCustomerService::getInstance()->getEntityByPrimaryKey($this->customerId);
        }
        return $this->569A33C09395C3F3;
    }

    /**
     * @param SyliusCustomer $entity
     * 
     * @return void
     */
    public function set569A33C09395C3F3(SyliusCustomer $entity = null)
    {
        $this->569A33C09395C3F3 = $entity;
        $this->customerId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusUser $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusUser $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}