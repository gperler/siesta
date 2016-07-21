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

class sylius_user_role implements ArraySerializable
{

    const TABLE_NAME = "sylius_user_role";

    const COLUMN_USER_ID = "user_id";

    const COLUMN_ROLE_ID = "role_id";

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
    protected $user_id;

    /**
     * @var int
     */
    protected $role_id;

    /**
     * @var sylius_user
     */
    protected $1DA28211A76ED395;

    /**
     * @var sylius_role
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
        $this->getUser_id(true, $connectionName);
        $this->getRole_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->user_id) . ',' . Escaper::quoteInt($this->role_id) . ');';
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
        $this->user_id = $resultSet->getIntegerValue("user_id");
        $this->role_id = $resultSet->getIntegerValue("role_id");
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
        $user_id = Escaper::quoteInt($this->user_id);
        $role_id = Escaper::quoteInt($this->role_id);
        $connection->execute("CALL sylius_user_role_DB_PK($user_id,$role_id)");
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
        $this->setUser_id($arrayAccessor->getIntegerValue("user_id"));
        $this->setRole_id($arrayAccessor->getIntegerValue("role_id"));
        $this->_existing = ($this->user_id !== null) && ($this->role_id !== null);
        $1DA28211A76ED395Array = $arrayAccessor->getArray("1DA28211A76ED395");
        if ($1DA28211A76ED395Array !== null) {
            $1DA28211A76ED395 = sylius_userService::getInstance()->newInstance();
            $1DA28211A76ED395->fromArray($1DA28211A76ED395Array);
            $this->set1DA28211A76ED395($1DA28211A76ED395);
        }
        $1DA28211D60322ACArray = $arrayAccessor->getArray("1DA28211D60322AC");
        if ($1DA28211D60322ACArray !== null) {
            $1DA28211D60322AC = sylius_roleService::getInstance()->newInstance();
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
            "user_id" => $this->getUser_id(),
            "role_id" => $this->getRole_id()
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
    public function getUser_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->user_id === null) {
            $this->user_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
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
     * @param bool $forceReload
     * 
     * @return sylius_user|null
     */
    public function get1DA28211A76ED395(bool $forceReload = false)
    {
        if ($this->1DA28211A76ED395 === null || $forceReload) {
            $this->1DA28211A76ED395 = sylius_userService::getInstance()->getEntityByPrimaryKey($this->user_id);
        }
        return $this->1DA28211A76ED395;
    }

    /**
     * @param sylius_user $entity
     * 
     * @return void
     */
    public function set1DA28211A76ED395(sylius_user $entity = null)
    {
        $this->1DA28211A76ED395 = $entity;
        $this->user_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_role|null
     */
    public function get1DA28211D60322AC(bool $forceReload = false)
    {
        if ($this->1DA28211D60322AC === null || $forceReload) {
            $this->1DA28211D60322AC = sylius_roleService::getInstance()->getEntityByPrimaryKey($this->role_id);
        }
        return $this->1DA28211D60322AC;
    }

    /**
     * @param sylius_role $entity
     * 
     * @return void
     */
    public function set1DA28211D60322AC(sylius_role $entity = null)
    {
        $this->1DA28211D60322AC = $entity;
        $this->role_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_user_role $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_user_role $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getUser_id() === $entity->getUser_id() && $this->getRole_id() === $entity->getRole_id();
    }

}