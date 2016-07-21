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

class SyliusApiRefreshToken implements ArraySerializable
{

    const TABLE_NAME = "sylius_api_refresh_token";

    const COLUMN_ID = "id";

    const COLUMN_CLIENTID = "client_id";

    const COLUMN_USERID = "user_id";

    const COLUMN_TOKEN = "token";

    const COLUMN_EXPIRESAT = "expires_at";

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
    protected $clientId;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @var SyliusApiClient
     */
    protected $4457852519EB6921;

    /**
     * @var SyliusUser
     */
    protected $44578525A76ED395;

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
        $spCall = ($this->_existing) ? "CALL sylius_api_refresh_token_U(" : "CALL sylius_api_refresh_token_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->clientId) . ',' . Escaper::quoteInt($this->userId) . ',' . Escaper::quoteString($connection, $this->token) . ',' . Escaper::quoteInt($this->expiresAt) . ',' . Escaper::quoteString($connection, $this->scope) . ');';
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
        if ($cascade && $this->4457852519EB6921 !== null) {
            $this->4457852519EB6921->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->44578525A76ED395 !== null) {
            $this->44578525A76ED395->save($cascade, $cycleDetector, $connectionName);
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
        $this->clientId = $resultSet->getIntegerValue("client_id");
        $this->userId = $resultSet->getIntegerValue("user_id");
        $this->token = $resultSet->getStringValue("token");
        $this->expiresAt = $resultSet->getIntegerValue("expires_at");
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
        $connection->execute("CALL sylius_api_refresh_token_DB_PK($id)");
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
        $this->setClientId($arrayAccessor->getIntegerValue("clientId"));
        $this->setUserId($arrayAccessor->getIntegerValue("userId"));
        $this->setToken($arrayAccessor->getStringValue("token"));
        $this->setExpiresAt($arrayAccessor->getIntegerValue("expiresAt"));
        $this->setScope($arrayAccessor->getStringValue("scope"));
        $this->_existing = ($this->id !== null);
        $4457852519EB6921Array = $arrayAccessor->getArray("4457852519EB6921");
        if ($4457852519EB6921Array !== null) {
            $4457852519EB6921 = SyliusApiClientService::getInstance()->newInstance();
            $4457852519EB6921->fromArray($4457852519EB6921Array);
            $this->set4457852519EB6921($4457852519EB6921);
        }
        $44578525A76ED395Array = $arrayAccessor->getArray("44578525A76ED395");
        if ($44578525A76ED395Array !== null) {
            $44578525A76ED395 = SyliusUserService::getInstance()->newInstance();
            $44578525A76ED395->fromArray($44578525A76ED395Array);
            $this->set44578525A76ED395($44578525A76ED395);
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
            "clientId" => $this->getClientId(),
            "userId" => $this->getUserId(),
            "token" => $this->getToken(),
            "expiresAt" => $this->getExpiresAt(),
            "scope" => $this->getScope()
        ];
        if ($this->4457852519EB6921 !== null) {
            $result["4457852519EB6921"] = $this->4457852519EB6921->toArray($cycleDetector);
        }
        if ($this->44578525A76ED395 !== null) {
            $result["44578525A76ED395"] = $this->44578525A76ED395->toArray($cycleDetector);
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
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     * 
     * @return void
     */
    public function setClientId(int $clientId = null)
    {
        $this->clientId = $clientId;
    }

    /**
     * 
     * @return int|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * 
     * @return void
     */
    public function setUserId(int $userId = null)
    {
        $this->userId = $userId;
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
     * @return int|null
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param int $expiresAt
     * 
     * @return void
     */
    public function setExpiresAt(int $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
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
     * @return SyliusApiClient|null
     */
    public function get4457852519EB6921(bool $forceReload = false)
    {
        if ($this->4457852519EB6921 === null || $forceReload) {
            $this->4457852519EB6921 = SyliusApiClientService::getInstance()->getEntityByPrimaryKey($this->clientId);
        }
        return $this->4457852519EB6921;
    }

    /**
     * @param SyliusApiClient $entity
     * 
     * @return void
     */
    public function set4457852519EB6921(SyliusApiClient $entity = null)
    {
        $this->4457852519EB6921 = $entity;
        $this->clientId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusUser|null
     */
    public function get44578525A76ED395(bool $forceReload = false)
    {
        if ($this->44578525A76ED395 === null || $forceReload) {
            $this->44578525A76ED395 = SyliusUserService::getInstance()->getEntityByPrimaryKey($this->userId);
        }
        return $this->44578525A76ED395;
    }

    /**
     * @param SyliusUser $entity
     * 
     * @return void
     */
    public function set44578525A76ED395(SyliusUser $entity = null)
    {
        $this->44578525A76ED395 = $entity;
        $this->userId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusApiRefreshToken $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusApiRefreshToken $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}