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

class SyliusUserRole implements ArraySerializable
{

    const TABLE_NAME = "sylius_user_role";

    const COLUMN_USERID = "user_id";

    const COLUMN_ROLEID = "role_id";

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
    protected $userId;

    /**
     * @var int
     */
    protected $roleId;

    /**
     * @var SyliusUser
     */
    protected $1DA28211A76ED395;

    /**
     * @var SyliusRole
     */
    protected $1DA28211D60322AC;

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
        $spCall = ($this->_existing) ? "CALL sylius_user_role_U(" : "CALL sylius_user_role_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getUserId(true, $connectionName);
        $this->getRoleId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->userId) . ',' . Escaper::quoteInt($this->roleId) . ');';
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
        if ($cascade && $this->1DA28211A76ED395 !== null) {
            $this->1DA28211A76ED395->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->1DA28211D60322AC !== null) {
            $this->1DA28211D60322AC->save($cascade, $cycleDetector, $connectionName);
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
        $this->userId = $resultSet->getIntegerValue("user_id");
        $this->roleId = $resultSet->getIntegerValue("role_id");
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
        $userId = Escaper::quoteInt($this->userId);
        $roleId = Escaper::quoteInt($this->roleId);
        $connection->execute("CALL sylius_user_role_DB_PK($userId,$roleId)");
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
        $this->setUserId($arrayAccessor->getIntegerValue("userId"));
        $this->setRoleId($arrayAccessor->getIntegerValue("roleId"));
        $this->_existing = ($this->userId !== null) && ($this->roleId !== null);
        $1DA28211A76ED395Array = $arrayAccessor->getArray("1DA28211A76ED395");
        if ($1DA28211A76ED395Array !== null) {
            $1DA28211A76ED395 = SyliusUserService::getInstance()->newInstance();
            $1DA28211A76ED395->fromArray($1DA28211A76ED395Array);
            $this->set1DA28211A76ED395($1DA28211A76ED395);
        }
        $1DA28211D60322ACArray = $arrayAccessor->getArray("1DA28211D60322AC");
        if ($1DA28211D60322ACArray !== null) {
            $1DA28211D60322AC = SyliusRoleService::getInstance()->newInstance();
            $1DA28211D60322AC->fromArray($1DA28211D60322ACArray);
            $this->set1DA28211D60322AC($1DA28211D60322AC);
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
            "userId" => $this->getUserId(),
            "roleId" => $this->getRoleId()
        ];
        if ($this->1DA28211A76ED395 !== null) {
            $result["1DA28211A76ED395"] = $this->1DA28211A76ED395->toArray($cycleDetector);
        }
        if ($this->1DA28211D60322AC !== null) {
            $result["1DA28211D60322AC"] = $this->1DA28211D60322AC->toArray($cycleDetector);
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
    public function getUserId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->userId === null) {
            $this->userId = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @param bool $forceReload
     * 
     * @return SyliusUser|null
     */
    public function get1DA28211A76ED395(bool $forceReload = false)
    {
        if ($this->1DA28211A76ED395 === null || $forceReload) {
            $this->1DA28211A76ED395 = SyliusUserService::getInstance()->getEntityByPrimaryKey($this->userId);
        }
        return $this->1DA28211A76ED395;
    }

    /**
     * @param SyliusUser $entity
     * 
     * @return void
     */
    public function set1DA28211A76ED395(SyliusUser $entity = null)
    {
        $this->1DA28211A76ED395 = $entity;
        $this->userId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusRole|null
     */
    public function get1DA28211D60322AC(bool $forceReload = false)
    {
        if ($this->1DA28211D60322AC === null || $forceReload) {
            $this->1DA28211D60322AC = SyliusRoleService::getInstance()->getEntityByPrimaryKey($this->roleId);
        }
        return $this->1DA28211D60322AC;
    }

    /**
     * @param SyliusRole $entity
     * 
     * @return void
     */
    public function set1DA28211D60322AC(SyliusRole $entity = null)
    {
        $this->1DA28211D60322AC = $entity;
        $this->roleId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusUserRole $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusUserRole $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getUserId() === $entity->getUserId() && $this->getRoleId() === $entity->getRoleId();
    }

}