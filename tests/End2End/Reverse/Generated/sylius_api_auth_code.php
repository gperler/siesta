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

class sylius_api_auth_code implements ArraySerializable
{

    const TABLE_NAME = "sylius_api_auth_code";

    const COLUMN_ID = "id";

    const COLUMN_CLIENT_ID = "client_id";

    const COLUMN_USER_ID = "user_id";

    const COLUMN_TOKEN = "token";

    const COLUMN_REDIRECT_URI = "redirect_uri";

    const COLUMN_EXPIRES_AT = "expires_at";

    const COLUMN_SCOPE = "scope";

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
    protected $client_id;

    /**
     * @var int
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $redirect_uri;

    /**
     * @var int
     */
    protected $expires_at;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @var sylius_api_client
     */
    protected $C840417919EB6921;

    /**
     * @var sylius_user
     */
    protected $C8404179A76ED395;

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
        $spCall = ($this->_existing) ? "CALL sylius_api_auth_code_U(" : "CALL sylius_api_auth_code_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->client_id) . ',' . Escaper::quoteInt($this->user_id) . ',' . Escaper::quoteString($connection, $this->token) . ',' . Escaper::quoteString($connection, $this->redirect_uri) . ',' . Escaper::quoteInt($this->expires_at) . ',' . Escaper::quoteString($connection, $this->scope) . ');';
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
        if ($cascade && $this->C840417919EB6921 !== null) {
            $this->C840417919EB6921->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->C8404179A76ED395 !== null) {
            $this->C8404179A76ED395->save($cascade, $cycleDetector, $connectionName);
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
        $this->client_id = $resultSet->getIntegerValue("client_id");
        $this->user_id = $resultSet->getIntegerValue("user_id");
        $this->token = $resultSet->getStringValue("token");
        $this->redirect_uri = $resultSet->getStringValue("redirect_uri");
        $this->expires_at = $resultSet->getIntegerValue("expires_at");
        $this->scope = $resultSet->getStringValue("scope");
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
        $connection->execute("CALL sylius_api_auth_code_DB_PK($id)");
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
        $this->setClient_id($arrayAccessor->getIntegerValue("client_id"));
        $this->setUser_id($arrayAccessor->getIntegerValue("user_id"));
        $this->setToken($arrayAccessor->getStringValue("token"));
        $this->setRedirect_uri($arrayAccessor->getStringValue("redirect_uri"));
        $this->setExpires_at($arrayAccessor->getIntegerValue("expires_at"));
        $this->setScope($arrayAccessor->getStringValue("scope"));
        $this->_existing = ($this->id !== null);
        $C840417919EB6921Array = $arrayAccessor->getArray("C840417919EB6921");
        if ($C840417919EB6921Array !== null) {
            $C840417919EB6921 = sylius_api_clientService::getInstance()->newInstance();
            $C840417919EB6921->fromArray($C840417919EB6921Array);
            $this->setC840417919EB6921($C840417919EB6921);
        }
        $C8404179A76ED395Array = $arrayAccessor->getArray("C8404179A76ED395");
        if ($C8404179A76ED395Array !== null) {
            $C8404179A76ED395 = sylius_userService::getInstance()->newInstance();
            $C8404179A76ED395->fromArray($C8404179A76ED395Array);
            $this->setC8404179A76ED395($C8404179A76ED395);
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
            "client_id" => $this->getClient_id(),
            "user_id" => $this->getUser_id(),
            "token" => $this->getToken(),
            "redirect_uri" => $this->getRedirect_uri(),
            "expires_at" => $this->getExpires_at(),
            "scope" => $this->getScope()
        ];
        if ($this->C840417919EB6921 !== null) {
            $result["C840417919EB6921"] = $this->C840417919EB6921->toArray($cycleDetector);
        }
        if ($this->C8404179A76ED395 !== null) {
            $result["C8404179A76ED395"] = $this->C8404179A76ED395->toArray($cycleDetector);
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
    public function getClient_id()
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     * 
     * @return void
     */
    public function setClient_id(int $client_id = null)
    {
        $this->client_id = $client_id;
    }

    /**
     * 
     * @return int|null
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * 
     * @return void
     */
    public function setUser_id(int $user_id = null)
    {
        $this->user_id = $user_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * 
     * @return void
     */
    public function setToken(string $token = null)
    {
        $this->token = StringUtil::trimToNull($token, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getRedirect_uri()
    {
        return $this->redirect_uri;
    }

    /**
     * @param string $redirect_uri
     * 
     * @return void
     */
    public function setRedirect_uri(string $redirect_uri = null)
    {
        $this->redirect_uri = StringUtil::trimToNull($redirect_uri, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getExpires_at()
    {
        return $this->expires_at;
    }

    /**
     * @param int $expires_at
     * 
     * @return void
     */
    public function setExpires_at(int $expires_at = null)
    {
        $this->expires_at = $expires_at;
    }

    /**
     * 
     * @return string|null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * 
     * @return void
     */
    public function setScope(string $scope = null)
    {
        $this->scope = StringUtil::trimToNull($scope, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_api_client|null
     */
    public function getC840417919EB6921(bool $forceReload = false)
    {
        if ($this->C840417919EB6921 === null || $forceReload) {
            $this->C840417919EB6921 = sylius_api_clientService::getInstance()->getEntityByPrimaryKey($this->client_id);
        }
        return $this->C840417919EB6921;
    }

    /**
     * @param sylius_api_client $entity
     * 
     * @return void
     */
    public function setC840417919EB6921(sylius_api_client $entity = null)
    {
        $this->C840417919EB6921 = $entity;
        $this->client_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_user|null
     */
    public function getC8404179A76ED395(bool $forceReload = false)
    {
        if ($this->C8404179A76ED395 === null || $forceReload) {
            $this->C8404179A76ED395 = sylius_userService::getInstance()->getEntityByPrimaryKey($this->user_id);
        }
        return $this->C8404179A76ED395;
    }

    /**
     * @param sylius_user $entity
     * 
     * @return void
     */
    public function setC8404179A76ED395(sylius_user $entity = null)
    {
        $this->C8404179A76ED395 = $entity;
        $this->user_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_api_auth_code $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_api_auth_code $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}