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

class SyliusPaymentSecurityToken implements ArraySerializable
{

    const TABLE_NAME = "sylius_payment_security_token";

    const COLUMN_HASH = "hash";

    const COLUMN_DETAILS = "details";

    const COLUMN_AFTERURL = "after_url";

    const COLUMN_TARGETURL = "target_url";

    const COLUMN_GATEWAYNAME = "gateway_name";

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
    protected $afterUrl;

    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * @var string
     */
    protected $gatewayName;

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
        return $spCall . Escaper::quoteString($connection, $this->hash) . ',' . Escaper::quoteString($connection, $this->details) . ',' . Escaper::quoteString($connection, $this->afterUrl) . ',' . Escaper::quoteString($connection, $this->targetUrl) . ',' . Escaper::quoteString($connection, $this->gatewayName) . ');';
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
        $this->afterUrl = $resultSet->getStringValue("after_url");
        $this->targetUrl = $resultSet->getStringValue("target_url");
        $this->gatewayName = $resultSet->getStringValue("gateway_name");
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
        $this->setAfterUrl($arrayAccessor->getStringValue("afterUrl"));
        $this->setTargetUrl($arrayAccessor->getStringValue("targetUrl"));
        $this->setGatewayName($arrayAccessor->getStringValue("gatewayName"));
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
            "afterUrl" => $this->getAfterUrl(),
            "targetUrl" => $this->getTargetUrl(),
            "gatewayName" => $this->getGatewayName()
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
    public function getAfterUrl()
    {
        return $this->afterUrl;
    }

    /**
     * @param string $afterUrl
     * 
     * @return void
     */
    public function setAfterUrl(string $afterUrl = null)
    {
        $this->afterUrl = StringUtil::trimToNull($afterUrl, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * @param string $targetUrl
     * 
     * @return void
     */
    public function setTargetUrl(string $targetUrl = null)
    {
        $this->targetUrl = StringUtil::trimToNull($targetUrl, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getGatewayName()
    {
        return $this->gatewayName;
    }

    /**
     * @param string $gatewayName
     * 
     * @return void
     */
    public function setGatewayName(string $gatewayName = null)
    {
        $this->gatewayName = StringUtil::trimToNull($gatewayName, 255);
    }

    /**
     * @param SyliusPaymentSecurityToken $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusPaymentSecurityToken $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getHash() === $entity->getHash();
    }

}