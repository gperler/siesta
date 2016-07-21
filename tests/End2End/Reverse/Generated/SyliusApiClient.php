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

class SyliusApiClient implements ArraySerializable
{

    const TABLE_NAME = "sylius_api_client";

    const COLUMN_ID = "id";

    const COLUMN_RANDOMID = "random_id";

    const COLUMN_REDIRECTURIS = "redirect_uris";

    const COLUMN_SECRET = "secret";

    const COLUMN_ALLOWEDGRANTTYPES = "allowed_grant_types";

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
     * @var string
     */
    protected $randomId;

    /**
     * @var string
     */
    protected $redirectUris;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $allowedGrantTypes;

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
        $spCall = ($this->_existing) ? "CALL sylius_api_client_U(" : "CALL sylius_api_client_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->randomId) . ',' . Escaper::quoteString($connection, $this->redirectUris) . ',' . Escaper::quoteString($connection, $this->secret) . ',' . Escaper::quoteString($connection, $this->allowedGrantTypes) . ');';
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
        $this->randomId = $resultSet->getStringValue("random_id");
        $this->redirectUris = $resultSet->getStringValue("redirect_uris");
        $this->secret = $resultSet->getStringValue("secret");
        $this->allowedGrantTypes = $resultSet->getStringValue("allowed_grant_types");
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
        $connection->execute("CALL sylius_api_client_DB_PK($id)");
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
        $this->setRandomId($arrayAccessor->getStringValue("randomId"));
        $this->setRedirectUris($arrayAccessor->getStringValue("redirectUris"));
        $this->setSecret($arrayAccessor->getStringValue("secret"));
        $this->setAllowedGrantTypes($arrayAccessor->getStringValue("allowedGrantTypes"));
        $this->_existing = ($this->id !== null);
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
            "randomId" => $this->getRandomId(),
            "redirectUris" => $this->getRedirectUris(),
            "secret" => $this->getSecret(),
            "allowedGrantTypes" => $this->getAllowedGrantTypes()
        ];
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
     * @return string|null
     */
    public function getRandomId()
    {
        return $this->randomId;
    }

    /**
     * @param string $randomId
     * 
     * @return void
     */
    public function setRandomId(string $randomId = null)
    {
        $this->randomId = StringUtil::trimToNull($randomId, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getRedirectUris()
    {
        return $this->redirectUris;
    }

    /**
     * @param string $redirectUris
     * 
     * @return void
     */
    public function setRedirectUris(string $redirectUris = null)
    {
        $this->redirectUris = StringUtil::trimToNull($redirectUris, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * 
     * @return void
     */
    public function setSecret(string $secret = null)
    {
        $this->secret = StringUtil::trimToNull($secret, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getAllowedGrantTypes()
    {
        return $this->allowedGrantTypes;
    }

    /**
     * @param string $allowedGrantTypes
     * 
     * @return void
     */
    public function setAllowedGrantTypes(string $allowedGrantTypes = null)
    {
        $this->allowedGrantTypes = StringUtil::trimToNull($allowedGrantTypes, null);
    }

    /**
     * @param SyliusApiClient $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusApiClient $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}