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

class sylius_api_client implements ArraySerializable
{

    const TABLE_NAME = "sylius_api_client";

    const COLUMN_ID = "id";

    const COLUMN_RANDOM_ID = "random_id";

    const COLUMN_REDIRECT_URIS = "redirect_uris";

    const COLUMN_SECRET = "secret";

    const COLUMN_ALLOWED_GRANT_TYPES = "allowed_grant_types";

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
    protected $random_id;

    /**
     * @var string
     */
    protected $redirect_uris;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $allowed_grant_types;

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
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->random_id) . ',' . Escaper::quoteString($connection, $this->redirect_uris) . ',' . Escaper::quoteString($connection, $this->secret) . ',' . Escaper::quoteString($connection, $this->allowed_grant_types) . ');';
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
        $this->random_id = $resultSet->getStringValue("random_id");
        $this->redirect_uris = $resultSet->getStringValue("redirect_uris");
        $this->secret = $resultSet->getStringValue("secret");
        $this->allowed_grant_types = $resultSet->getStringValue("allowed_grant_types");
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
        $this->setRandom_id($arrayAccessor->getStringValue("random_id"));
        $this->setRedirect_uris($arrayAccessor->getStringValue("redirect_uris"));
        $this->setSecret($arrayAccessor->getStringValue("secret"));
        $this->setAllowed_grant_types($arrayAccessor->getStringValue("allowed_grant_types"));
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
            "random_id" => $this->getRandom_id(),
            "redirect_uris" => $this->getRedirect_uris(),
            "secret" => $this->getSecret(),
            "allowed_grant_types" => $this->getAllowed_grant_types()
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
    public function getRandom_id()
    {
        return $this->random_id;
    }

    /**
     * @param string $random_id
     * 
     * @return void
     */
    public function setRandom_id(string $random_id = null)
    {
        $this->random_id = StringUtil::trimToNull($random_id, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getRedirect_uris()
    {
        return $this->redirect_uris;
    }

    /**
     * @param string $redirect_uris
     * 
     * @return void
     */
    public function setRedirect_uris(string $redirect_uris = null)
    {
        $this->redirect_uris = StringUtil::trimToNull($redirect_uris, null);
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
    public function getAllowed_grant_types()
    {
        return $this->allowed_grant_types;
    }

    /**
     * @param string $allowed_grant_types
     * 
     * @return void
     */
    public function setAllowed_grant_types(string $allowed_grant_types = null)
    {
        $this->allowed_grant_types = StringUtil::trimToNull($allowed_grant_types, null);
    }

    /**
     * @param sylius_api_client $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_api_client $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}