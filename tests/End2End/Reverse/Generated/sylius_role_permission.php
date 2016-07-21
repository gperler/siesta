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

class sylius_role_permission implements ArraySerializable
{

    const TABLE_NAME = "sylius_role_permission";

    const COLUMN_ROLE_ID = "role_id";

    const COLUMN_PERMISSION_ID = "permission_id";

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
    protected $role_id;

    /**
     * @var int
     */
    protected $permission_id;

    /**
     * @var sylius_role
     */
    protected $45CEE9B8D60322AC;

    /**
     * @var sylius_permission
     */
    protected $45CEE9B8FED90CCA;

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
        $spCall = ($this->_existing) ? "CALL sylius_role_permission_U(" : "CALL sylius_role_permission_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getRole_id(true, $connectionName);
        $this->getPermission_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->role_id) . ',' . Escaper::quoteInt($this->permission_id) . ');';
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
        if ($cascade && $this->45CEE9B8D60322AC !== null) {
            $this->45CEE9B8D60322AC->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->45CEE9B8FED90CCA !== null) {
            $this->45CEE9B8FED90CCA->save($cascade, $cycleDetector, $connectionName);
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
        $this->role_id = $resultSet->getIntegerValue("role_id");
        $this->permission_id = $resultSet->getIntegerValue("permission_id");
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
        $role_id = Escaper::quoteInt($this->role_id);
        $permission_id = Escaper::quoteInt($this->permission_id);
        $connection->execute("CALL sylius_role_permission_DB_PK($role_id,$permission_id)");
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
        $this->setRole_id($arrayAccessor->getIntegerValue("role_id"));
        $this->setPermission_id($arrayAccessor->getIntegerValue("permission_id"));
        $this->_existing = ($this->role_id !== null) && ($this->permission_id !== null);
        $45CEE9B8D60322ACArray = $arrayAccessor->getArray("45CEE9B8D60322AC");
        if ($45CEE9B8D60322ACArray !== null) {
            $45CEE9B8D60322AC = sylius_roleService::getInstance()->newInstance();
            $45CEE9B8D60322AC->fromArray($45CEE9B8D60322ACArray);
            $this->set45CEE9B8D60322AC($45CEE9B8D60322AC);
        }
        $45CEE9B8FED90CCAArray = $arrayAccessor->getArray("45CEE9B8FED90CCA");
        if ($45CEE9B8FED90CCAArray !== null) {
            $45CEE9B8FED90CCA = sylius_permissionService::getInstance()->newInstance();
            $45CEE9B8FED90CCA->fromArray($45CEE9B8FED90CCAArray);
            $this->set45CEE9B8FED90CCA($45CEE9B8FED90CCA);
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
            "role_id" => $this->getRole_id(),
            "permission_id" => $this->getPermission_id()
        ];
        if ($this->45CEE9B8D60322AC !== null) {
            $result["45CEE9B8D60322AC"] = $this->45CEE9B8D60322AC->toArray($cycleDetector);
        }
        if ($this->45CEE9B8FED90CCA !== null) {
            $result["45CEE9B8FED90CCA"] = $this->45CEE9B8FED90CCA->toArray($cycleDetector);
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
    public function getRole_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->role_id === null) {
            $this->role_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->role_id;
    }

    /**
     * @param int $role_id
     * 
     * @return void
     */
    public function setRole_id(int $role_id = null)
    {
        $this->role_id = $role_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getPermission_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->permission_id === null) {
            $this->permission_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->permission_id;
    }

    /**
     * @param int $permission_id
     * 
     * @return void
     */
    public function setPermission_id(int $permission_id = null)
    {
        $this->permission_id = $permission_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_role|null
     */
    public function get45CEE9B8D60322AC(bool $forceReload = false)
    {
        if ($this->45CEE9B8D60322AC === null || $forceReload) {
            $this->45CEE9B8D60322AC = sylius_roleService::getInstance()->getEntityByPrimaryKey($this->role_id);
        }
        return $this->45CEE9B8D60322AC;
    }

    /**
     * @param sylius_role $entity
     * 
     * @return void
     */
    public function set45CEE9B8D60322AC(sylius_role $entity = null)
    {
        $this->45CEE9B8D60322AC = $entity;
        $this->role_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_permission|null
     */
    public function get45CEE9B8FED90CCA(bool $forceReload = false)
    {
        if ($this->45CEE9B8FED90CCA === null || $forceReload) {
            $this->45CEE9B8FED90CCA = sylius_permissionService::getInstance()->getEntityByPrimaryKey($this->permission_id);
        }
        return $this->45CEE9B8FED90CCA;
    }

    /**
     * @param sylius_permission $entity
     * 
     * @return void
     */
    public function set45CEE9B8FED90CCA(sylius_permission $entity = null)
    {
        $this->45CEE9B8FED90CCA = $entity;
        $this->permission_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_role_permission $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_role_permission $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getRole_id() === $entity->getRole_id() && $this->getPermission_id() === $entity->getPermission_id();
    }

}