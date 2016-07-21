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

class SyliusApiAuthCode implements ArraySerializable
{

    const TABLE_NAME = "sylius_api_auth_code";

    const COLUMN_ID = "id";

    const COLUMN_CLIENTID = "client_id";

    const COLUMN_USERID = "user_id";

    const COLUMN_TOKEN = "token";

    const COLUMN_REDIRECTURI = "redirect_uri";

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
     * @var string
     */
    protected $redirectUri;

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
    protected $C840417919EB6921;

    /**
     * @var SyliusUser
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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->clientId) . ',' . Escaper::quoteInt($this->userId) . ',' . Escaper::quoteString($connection, $this->token) . ',' . Escaper::quoteString($connection, $this->redirectUri) . ',' . Escaper::quoteInt($this->expiresAt) . ',' . Escaper::quoteString($connection, $this->scope) . ');';
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
        $this->clientId = $resultSet->getIntegerValue("client_id");
        $this->userId = $resultSet->getIntegerValue("user_id");
        $this->token = $resultSet->getStringValue("token");
        $this->redirectUri = $resultSet->getStringValue("redirect_uri");
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
        $this->setClientId($arrayAccessor->getIntegerValue("clientId"));
        $this->setUserId($arrayAccessor->getIntegerValue("userId"));
        $this->setToken($arrayAccessor->getStringValue("token"));
        $this->setRedirectUri($arrayAccessor->getStringValue("redirectUri"));
        $this->setExpiresAt($arrayAccessor->getIntegerValue("expiresAt"));
        $this->setScope($arrayAccessor->getStringValue("scope"));
        $this->_existing = ($this->id !== null);
        $C840417919EB6921Array = $arrayAccessor->getArray("C840417919EB6921");
        if ($C840417919EB6921Array !== null) {
            $C840417919EB6921 = SyliusApiClientService::getInstance()->newInstance();
            $C840417919EB6921->fromArray($C840417919EB6921Array);
            $this->setC840417919EB6921($C840417919EB6921);
        }
        $C8404179A76ED395Array = $arrayAccessor->getArray("C8404179A76ED395");
        if ($C8404179A76ED395Array !== null) {
            $C8404179A76ED395 = SyliusUserService::getInstance()->newInstance();
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
            "clientId" => $this->getClientId(),
            "userId" => $this->getUserId(),
            "token" => $this->getToken(),
            "redirectUri" => $this->getRedirectUri(),
            "expiresAt" => $this->getExpiresAt(),
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
     * @return string|null
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     * 
     * @return void
     */
    public function setRedirectUri(string $redirectUri = null)
    {
        $this->redirectUri = StringUtil::trimToNull($redirectUri, null);
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
    public function getC840417919EB6921(bool $forceReload = false)
    {
        if ($this->C840417919EB6921 === null || $forceReload) {
            $this->C840417919EB6921 = SyliusApiClientService::getInstance()->getEntityByPrimaryKey($this->clientId);
        }
        return $this->C840417919EB6921;
    }

    /**
     * @param SyliusApiClient $entity
     * 
     * @return void
     */
    public function setC840417919EB6921(SyliusApiClient $entity = null)
    {
        $this->C840417919EB6921 = $entity;
        $this->clientId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusUser|null
     */
    public function getC8404179A76ED395(bool $forceReload = false)
    {
        if ($this->C8404179A76ED395 === null || $forceReload) {
            $this->C8404179A76ED395 = SyliusUserService::getInstance()->getEntityByPrimaryKey($this->userId);
        }
        return $this->C8404179A76ED395;
    }

    /**
     * @param SyliusUser $entity
     * 
     * @return void
     */
    public function setC8404179A76ED395(SyliusUser $entity = null)
    {
        $this->C8404179A76ED395 = $entity;
        $this->userId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusApiAuthCode $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusApiAuthCode $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}