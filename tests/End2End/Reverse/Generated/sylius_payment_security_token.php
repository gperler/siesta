<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\StringUtil;

class sylius_payment_security_token implements ArraySerializable
{

    const TABLE_NAME = "sylius_payment_security_token";

    const COLUMN_HASH = "hash";

    const COLUMN_DETAILS = "details";

    const COLUMN_AFTER_URL = "after_url";

    const COLUMN_TARGET_URL = "target_url";

    const COLUMN_GATEWAY_NAME = "gateway_name";

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
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $details;

    /**
     * @var string
     */
    protected $after_url;

    /**
     * @var string
     */
    protected $target_url;

    /**
     * @var string
     */
    protected $gateway_name;

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
        $spCall = ($this->_existing) ? "CALL sylius_payment_security_token_U(" : "CALL sylius_payment_security_token_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getHash(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->hash) . ',' . Escaper::quoteString($connection, $this->details) . ',' . Escaper::quoteString($connection, $this->after_url) . ',' . Escaper::quoteString($connection, $this->target_url) . ',' . Escaper::quoteString($connection, $this->gateway_name) . ');';
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
        $this->hash = $resultSet->getStringValue("hash");
        $this->details = $resultSet->getStringValue("details");
        $this->after_url = $resultSet->getStringValue("after_url");
        $this->target_url = $resultSet->getStringValue("target_url");
        $this->gateway_name = $resultSet->getStringValue("gateway_name");
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
        $hash = Escaper::quoteString($connection, $this->hash);
        $connection->execute("CALL sylius_payment_security_token_DB_PK($hash)");
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
        $this->setHash($arrayAccessor->getStringValue("hash"));
        $this->setDetails($arrayAccessor->getStringValue("details"));
        $this->setAfter_url($arrayAccessor->getStringValue("after_url"));
        $this->setTarget_url($arrayAccessor->getStringValue("target_url"));
        $this->setGateway_name($arrayAccessor->getStringValue("gateway_name"));
        $this->_existing = ($this->hash !== null);
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
            "hash" => $this->getHash(),
            "details" => $this->getDetails(),
            "after_url" => $this->getAfter_url(),
            "target_url" => $this->getTarget_url(),
            "gateway_name" => $this->getGateway_name()
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
     * @return string|null
     */
    public function getHash(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->hash === null) {
            $this->hash = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->hash;
    }

    /**
     * @param string $hash
     * 
     * @return void
     */
    public function setHash(string $hash = null)
    {
        $this->hash = StringUtil::trimToNull($hash, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     * 
     * @return void
     */
    public function setDetails(string $details = null)
    {
        $this->details = StringUtil::trimToNull($details, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getAfter_url()
    {
        return $this->after_url;
    }

    /**
     * @param string $after_url
     * 
     * @return void
     */
    public function setAfter_url(string $after_url = null)
    {
        $this->after_url = StringUtil::trimToNull($after_url, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getTarget_url()
    {
        return $this->target_url;
    }

    /**
     * @param string $target_url
     * 
     * @return void
     */
    public function setTarget_url(string $target_url = null)
    {
        $this->target_url = StringUtil::trimToNull($target_url, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getGateway_name()
    {
        return $this->gateway_name;
    }

    /**
     * @param string $gateway_name
     * 
     * @return void
     */
    public function setGateway_name(string $gateway_name = null)
    {
        $this->gateway_name = StringUtil::trimToNull($gateway_name, 255);
    }

    /**
     * @param sylius_payment_security_token $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_payment_security_token $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getHash() === $entity->getHash();
    }

}