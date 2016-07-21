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

class SyliusRolePermission implements ArraySerializable
{

    const TABLE_NAME = "sylius_role_permission";

    const COLUMN_ROLEID = "role_id";

    const COLUMN_PERMISSIONID = "permission_id";

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
    protected $roleId;

    /**
     * @var int
     */
    protected $permissionId;

    /**
     * @var SyliusRole
     */
    protected $45CEE9B8D60322AC;

    /**
     * @var SyliusPermission
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
        $this->getRoleId(true, $connectionName);
        $this->getPermissionId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->roleId) . ',' . Escaper::quoteInt($this->permissionId) . ');';
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
        $this->roleId = $resultSet->getIntegerValue("role_id");
        $this->permissionId = $resultSet->getIntegerValue("permission_id");
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
        $roleId = Escaper::quoteInt($this->roleId);
        $permissionId = Escaper::quoteInt($this->permissionId);
        $connection->execute("CALL sylius_role_permission_DB_PK($roleId,$permissionId)");
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
        $this->setRoleId($arrayAccessor->getIntegerValue("roleId"));
        $this->setPermissionId($arrayAccessor->getIntegerValue("permissionId"));
        $this->_existing = ($this->roleId !== null) && ($this->permissionId !== null);
        $45CEE9B8D60322ACArray = $arrayAccessor->getArray("45CEE9B8D60322AC");
        if ($45CEE9B8D60322ACArray !== null) {
            $45CEE9B8D60322AC = SyliusRoleService::getInstance()->newInstance();
            $45CEE9B8D60322AC->fromArray($45CEE9B8D60322ACArray);
            $this->set45CEE9B8D60322AC($45CEE9B8D60322AC);
        }
        $45CEE9B8FED90CCAArray = $arrayAccessor->getArray("45CEE9B8FED90CCA");
        if ($45CEE9B8FED90CCAArray !== null) {
            $45CEE9B8FED90CCA = SyliusPermissionService::getInstance()->newInstance();
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
            "roleId" => $this->getRoleId(),
            "permissionId" => $this->getPermissionId()
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
    public function getRoleId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->roleId === null) {
            $this->roleId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->roleId;
    }

    /**
     * @param int $roleId
     * 
     * @return void
     */
    public function setRoleId(int $roleId = null)
    {
        $this->roleId = $roleId;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getPermissionId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->permissionId === null) {
            $this->permissionId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->permissionId;
    }

    /**
     * @param int $permissionId
     * 
     * @return void
     */
    public function setPermissionId(int $permissionId = null)
    {
        $this->permissionId = $permissionId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusRole|null
     */
    public function get45CEE9B8D60322AC(bool $forceReload = false)
    {
        if ($this->45CEE9B8D60322AC === null || $forceReload) {
            $this->45CEE9B8D60322AC = SyliusRoleService::getInstance()->getEntityByPrimaryKey($this->roleId);
        }
        return $this->45CEE9B8D60322AC;
    }

    /**
     * @param SyliusRole $entity
     * 
     * @return void
     */
    public function set45CEE9B8D60322AC(SyliusRole $entity = null)
    {
        $this->45CEE9B8D60322AC = $entity;
        $this->roleId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusPermission|null
     */
    public function get45CEE9B8FED90CCA(bool $forceReload = false)
    {
        if ($this->45CEE9B8FED90CCA === null || $forceReload) {
            $this->45CEE9B8FED90CCA = SyliusPermissionService::getInstance()->getEntityByPrimaryKey($this->permissionId);
        }
        return $this->45CEE9B8FED90CCA;
    }

    /**
     * @param SyliusPermission $entity
     * 
     * @return void
     */
    public function set45CEE9B8FED90CCA(SyliusPermission $entity = null)
    {
        $this->45CEE9B8FED90CCA = $entity;
        $this->permissionId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusRolePermission $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusRolePermission $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getRoleId() === $entity->getRoleId() && $this->getPermissionId() === $entity->getPermissionId();
    }

}