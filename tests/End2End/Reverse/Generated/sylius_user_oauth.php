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

class sylius_user_oauth implements ArraySerializable
{

    const TABLE_NAME = "sylius_user_oauth";

    const COLUMN_ID = "id";

    const COLUMN_USER_ID = "user_id";

    const COLUMN_PROVIDER = "provider";

    const COLUMN_IDENTIFIER = "identifier";

    const COLUMN_ACCESS_TOKEN = "access_token";

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
    protected $user_id;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $access_token;

    /**
     * @var sylius_user
     */
    protected $C3471B78A76ED395;

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
        $spCall = ($this->_existing) ? "CALL sylius_user_oauth_U(" : "CALL sylius_user_oauth_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->user_id) . ',' . Escaper::quoteString($connection, $this->provider) . ',' . Escaper::quoteString($connection, $this->identifier) . ',' . Escaper::quoteString($connection, $this->access_token) . ');';
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
        if ($cascade && $this->C3471B78A76ED395 !== null) {
            $this->C3471B78A76ED395->save($cascade, $cycleDetector, $connectionName);
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
        $this->user_id = $resultSet->getIntegerValue("user_id");
        $this->provider = $resultSet->getStringValue("provider");
        $this->identifier = $resultSet->getStringValue("identifier");
        $this->access_token = $resultSet->getStringValue("access_token");
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
        $connection->execute("CALL sylius_user_oauth_DB_PK($id)");
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
        $this->setUser_id($arrayAccessor->getIntegerValue("user_id"));
        $this->setProvider($arrayAccessor->getStringValue("provider"));
        $this->setIdentifier($arrayAccessor->getStringValue("identifier"));
        $this->setAccess_token($arrayAccessor->getStringValue("access_token"));
        $this->_existing = ($this->id !== null);
        $C3471B78A76ED395Array = $arrayAccessor->getArray("C3471B78A76ED395");
        if ($C3471B78A76ED395Array !== null) {
            $C3471B78A76ED395 = sylius_userService::getInstance()->newInstance();
            $C3471B78A76ED395->fromArray($C3471B78A76ED395Array);
            $this->setC3471B78A76ED395($C3471B78A76ED395);
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
            "user_id" => $this->getUser_id(),
            "provider" => $this->getProvider(),
            "identifier" => $this->getIdentifier(),
            "access_token" => $this->getAccess_token()
        ];
        if ($this->C3471B78A76ED395 !== null) {
            $result["C3471B78A76ED395"] = $this->C3471B78A76ED395->toArray($cycleDetector);
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
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * 
     * @return void
     */
    public function setProvider(string $provider = null)
    {
        $this->provider = StringUtil::trimToNull($provider, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * 
     * @return void
     */
    public function setIdentifier(string $identifier = null)
    {
        $this->identifier = StringUtil::trimToNull($identifier, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getAccess_token()
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     * 
     * @return void
     */
    public function setAccess_token(string $access_token = null)
    {
        $this->access_token = StringUtil::trimToNull($access_token, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_user|null
     */
    public function getC3471B78A76ED395(bool $forceReload = false)
    {
        if ($this->C3471B78A76ED395 === null || $forceReload) {
            $this->C3471B78A76ED395 = sylius_userService::getInstance()->getEntityByPrimaryKey($this->user_id);
        }
        return $this->C3471B78A76ED395;
    }

    /**
     * @param sylius_user $entity
     * 
     * @return void
     */
    public function setC3471B78A76ED395(sylius_user $entity = null)
    {
        $this->C3471B78A76ED395 = $entity;
        $this->user_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_user_oauth $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_user_oauth $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}