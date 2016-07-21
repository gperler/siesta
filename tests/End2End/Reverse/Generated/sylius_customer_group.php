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

class sylius_customer_group implements ArraySerializable
{

    const TABLE_NAME = "sylius_customer_group";

    const COLUMN_CUSTOMER_ID = "customer_id";

    const COLUMN_GROUP_ID = "group_id";

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
    protected $customer_id;

    /**
     * @var int
     */
    protected $group_id;

    /**
     * @var sylius_customer
     */
    protected $7FCF9B059395C3F3;

    /**
     * @var sylius_group
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
        $this->getCustomer_id(true, $connectionName);
        $this->getGroup_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->customer_id) . ',' . Escaper::quoteInt($this->group_id) . ');';
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
        $this->customer_id = $resultSet->getIntegerValue("customer_id");
        $this->group_id = $resultSet->getIntegerValue("group_id");
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
        $customer_id = Escaper::quoteInt($this->customer_id);
        $group_id = Escaper::quoteInt($this->group_id);
        $connection->execute("CALL sylius_customer_group_DB_PK($customer_id,$group_id)");
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
        $this->setCustomer_id($arrayAccessor->getIntegerValue("customer_id"));
        $this->setGroup_id($arrayAccessor->getIntegerValue("group_id"));
        $this->_existing = ($this->customer_id !== null) && ($this->group_id !== null);
        $7FCF9B059395C3F3Array = $arrayAccessor->getArray("7FCF9B059395C3F3");
        if ($7FCF9B059395C3F3Array !== null) {
            $7FCF9B059395C3F3 = sylius_customerService::getInstance()->newInstance();
            $7FCF9B059395C3F3->fromArray($7FCF9B059395C3F3Array);
            $this->set7FCF9B059395C3F3($7FCF9B059395C3F3);
        }
        $7FCF9B05FE54D947Array = $arrayAccessor->getArray("7FCF9B05FE54D947");
        if ($7FCF9B05FE54D947Array !== null) {
            $7FCF9B05FE54D947 = sylius_groupService::getInstance()->newInstance();
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
            "customer_id" => $this->getCustomer_id(),
            "group_id" => $this->getGroup_id()
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
    public function getCustomer_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->customer_id === null) {
            $this->customer_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * 
     * @return void
     */
    public function setCustomer_id(int $customer_id = null)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getGroup_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->group_id === null) {
            $this->group_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->group_id;
    }

    /**
     * @param int $group_id
     * 
     * @return void
     */
    public function setGroup_id(int $group_id = null)
    {
        $this->group_id = $group_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_customer|null
     */
    public function get7FCF9B059395C3F3(bool $forceReload = false)
    {
        if ($this->7FCF9B059395C3F3 === null || $forceReload) {
            $this->7FCF9B059395C3F3 = sylius_customerService::getInstance()->getEntityByPrimaryKey($this->customer_id);
        }
        return $this->7FCF9B059395C3F3;
    }

    /**
     * @param sylius_customer $entity
     * 
     * @return void
     */
    public function set7FCF9B059395C3F3(sylius_customer $entity = null)
    {
        $this->7FCF9B059395C3F3 = $entity;
        $this->customer_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_group|null
     */
    public function get7FCF9B05FE54D947(bool $forceReload = false)
    {
        if ($this->7FCF9B05FE54D947 === null || $forceReload) {
            $this->7FCF9B05FE54D947 = sylius_groupService::getInstance()->getEntityByPrimaryKey($this->group_id);
        }
        return $this->7FCF9B05FE54D947;
    }

    /**
     * @param sylius_group $entity
     * 
     * @return void
     */
    public function set7FCF9B05FE54D947(sylius_group $entity = null)
    {
        $this->7FCF9B05FE54D947 = $entity;
        $this->group_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_customer_group $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_customer_group $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getCustomer_id() === $entity->getCustomer_id() && $this->getGroup_id() === $entity->getGroup_id();
    }

}