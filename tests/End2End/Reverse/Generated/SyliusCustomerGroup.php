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

class SyliusCustomerGroup implements ArraySerializable
{

    const TABLE_NAME = "sylius_customer_group";

    const COLUMN_CUSTOMERID = "customer_id";

    const COLUMN_GROUPID = "group_id";

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
    protected $customerId;

    /**
     * @var int
     */
    protected $groupId;

    /**
     * @var SyliusCustomer
     */
    protected $7FCF9B059395C3F3;

    /**
     * @var SyliusGroup
     */
    protected $7FCF9B05FE54D947;

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
        $spCall = ($this->_existing) ? "CALL sylius_customer_group_U(" : "CALL sylius_customer_group_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getCustomerId(true, $connectionName);
        $this->getGroupId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->customerId) . ',' . Escaper::quoteInt($this->groupId) . ');';
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
        if ($cascade && $this->7FCF9B059395C3F3 !== null) {
            $this->7FCF9B059395C3F3->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->7FCF9B05FE54D947 !== null) {
            $this->7FCF9B05FE54D947->save($cascade, $cycleDetector, $connectionName);
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
        $this->customerId = $resultSet->getIntegerValue("customer_id");
        $this->groupId = $resultSet->getIntegerValue("group_id");
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
        $customerId = Escaper::quoteInt($this->customerId);
        $groupId = Escaper::quoteInt($this->groupId);
        $connection->execute("CALL sylius_customer_group_DB_PK($customerId,$groupId)");
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
        $this->setCustomerId($arrayAccessor->getIntegerValue("customerId"));
        $this->setGroupId($arrayAccessor->getIntegerValue("groupId"));
        $this->_existing = ($this->customerId !== null) && ($this->groupId !== null);
        $7FCF9B059395C3F3Array = $arrayAccessor->getArray("7FCF9B059395C3F3");
        if ($7FCF9B059395C3F3Array !== null) {
            $7FCF9B059395C3F3 = SyliusCustomerService::getInstance()->newInstance();
            $7FCF9B059395C3F3->fromArray($7FCF9B059395C3F3Array);
            $this->set7FCF9B059395C3F3($7FCF9B059395C3F3);
        }
        $7FCF9B05FE54D947Array = $arrayAccessor->getArray("7FCF9B05FE54D947");
        if ($7FCF9B05FE54D947Array !== null) {
            $7FCF9B05FE54D947 = SyliusGroupService::getInstance()->newInstance();
            $7FCF9B05FE54D947->fromArray($7FCF9B05FE54D947Array);
            $this->set7FCF9B05FE54D947($7FCF9B05FE54D947);
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
            "customerId" => $this->getCustomerId(),
            "groupId" => $this->getGroupId()
        ];
        if ($this->7FCF9B059395C3F3 !== null) {
            $result["7FCF9B059395C3F3"] = $this->7FCF9B059395C3F3->toArray($cycleDetector);
        }
        if ($this->7FCF9B05FE54D947 !== null) {
            $result["7FCF9B05FE54D947"] = $this->7FCF9B05FE54D947->toArray($cycleDetector);
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
    public function getCustomerId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->customerId === null) {
            $this->customerId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getGroupId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->groupId === null) {
            $this->groupId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->groupId;
    }

    /**
     * @param int $groupId
     * 
     * @return void
     */
    public function setGroupId(int $groupId = null)
    {
        $this->groupId = $groupId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusCustomer|null
     */
    public function get7FCF9B059395C3F3(bool $forceReload = false)
    {
        if ($this->7FCF9B059395C3F3 === null || $forceReload) {
            $this->7FCF9B059395C3F3 = SyliusCustomerService::getInstance()->getEntityByPrimaryKey($this->customerId);
        }
        return $this->7FCF9B059395C3F3;
    }

    /**
     * @param SyliusCustomer $entity
     * 
     * @return void
     */
    public function set7FCF9B059395C3F3(SyliusCustomer $entity = null)
    {
        $this->7FCF9B059395C3F3 = $entity;
        $this->customerId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusGroup|null
     */
    public function get7FCF9B05FE54D947(bool $forceReload = false)
    {
        if ($this->7FCF9B05FE54D947 === null || $forceReload) {
            $this->7FCF9B05FE54D947 = SyliusGroupService::getInstance()->getEntityByPrimaryKey($this->groupId);
        }
        return $this->7FCF9B05FE54D947;
    }

    /**
     * @param SyliusGroup $entity
     * 
     * @return void
     */
    public function set7FCF9B05FE54D947(SyliusGroup $entity = null)
    {
        $this->7FCF9B05FE54D947 = $entity;
        $this->groupId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusCustomerGroup $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusCustomerGroup $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getCustomerId() === $entity->getCustomerId() && $this->getGroupId() === $entity->getGroupId();
    }

}