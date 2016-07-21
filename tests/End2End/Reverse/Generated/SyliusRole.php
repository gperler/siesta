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
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class SyliusRole implements ArraySerializable
{

    const TABLE_NAME = "sylius_role";

    const COLUMN_ID = "id";

    const COLUMN_PARENTID = "parent_id";

    const COLUMN_CODE = "code";

    const COLUMN_NAME = "name";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_SECURITYROLES = "security_roles";

    const COLUMN_TREELEFT = "tree_left";

    const COLUMN_TREERIGHT = "tree_right";

    const COLUMN_TREELEVEL = "tree_level";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

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
     * @var int
     */
    protected $parentId;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $securityRoles;

    /**
     * @var int
     */
    protected $treeLeft;

    /**
     * @var int
     */
    protected $treeRight;

    /**
     * @var int
     */
    protected $treeLevel;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusRole
     */
    protected $8C606FE3727ACA70;

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
        $spCall = ($this->_existing) ? "CALL sylius_role_U(" : "CALL sylius_role_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->parentId) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteString($connection, $this->securityRoles) . ',' . Escaper::quoteInt($this->treeLeft) . ',' . Escaper::quoteInt($this->treeRight) . ',' . Escaper::quoteInt($this->treeLevel) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        if ($cascade && $this->8C606FE3727ACA70 !== null) {
            $this->8C606FE3727ACA70->save($cascade, $cycleDetector, $connectionName);
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
        $this->parentId = $resultSet->getIntegerValue("parent_id");
        $this->code = $resultSet->getStringValue("code");
        $this->name = $resultSet->getStringValue("name");
        $this->description = $resultSet->getStringValue("description");
        $this->securityRoles = $resultSet->getStringValue("security_roles");
        $this->treeLeft = $resultSet->getIntegerValue("tree_left");
        $this->treeRight = $resultSet->getIntegerValue("tree_right");
        $this->treeLevel = $resultSet->getIntegerValue("tree_level");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
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
        $connection->execute("CALL sylius_role_DB_PK($id)");
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
        $this->setParentId($arrayAccessor->getIntegerValue("parentId"));
        $this->setCode($arrayAccessor->getStringValue("code"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setSecurityRoles($arrayAccessor->getStringValue("securityRoles"));
        $this->setTreeLeft($arrayAccessor->getIntegerValue("treeLeft"));
        $this->setTreeRight($arrayAccessor->getIntegerValue("treeRight"));
        $this->setTreeLevel($arrayAccessor->getIntegerValue("treeLevel"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $8C606FE3727ACA70Array = $arrayAccessor->getArray("8C606FE3727ACA70");
        if ($8C606FE3727ACA70Array !== null) {
            $8C606FE3727ACA70 = SyliusRoleService::getInstance()->newInstance();
            $8C606FE3727ACA70->fromArray($8C606FE3727ACA70Array);
            $this->set8C606FE3727ACA70($8C606FE3727ACA70);
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
            "id" => $this->getId(),
            "parentId" => $this->getParentId(),
            "code" => $this->getCode(),
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "securityRoles" => $this->getSecurityRoles(),
            "treeLeft" => $this->getTreeLeft(),
            "treeRight" => $this->getTreeRight(),
            "treeLevel" => $this->getTreeLevel(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
        ];
        if ($this->8C606FE3727ACA70 !== null) {
            $result["8C606FE3727ACA70"] = $this->8C606FE3727ACA70->toArray($cycleDetector);
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
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     * 
     * @return void
     */
    public function setParentId(int $parentId = null)
    {
        $this->parentId = $parentId;
    }

    /**
     * 
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * 
     * @return void
     */
    public function setCode(string $code = null)
    {
        $this->code = StringUtil::trimToNull($code, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name = null)
    {
        $this->name = StringUtil::trimToNull($name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return void
     */
    public function setDescription(string $description = null)
    {
        $this->description = StringUtil::trimToNull($description, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getSecurityRoles()
    {
        return $this->securityRoles;
    }

    /**
     * @param string $securityRoles
     * 
     * @return void
     */
    public function setSecurityRoles(string $securityRoles = null)
    {
        $this->securityRoles = StringUtil::trimToNull($securityRoles, null);
    }

    /**
     * 
     * @return int|null
     */
    public function getTreeLeft()
    {
        return $this->treeLeft;
    }

    /**
     * @param int $treeLeft
     * 
     * @return void
     */
    public function setTreeLeft(int $treeLeft = null)
    {
        $this->treeLeft = $treeLeft;
    }

    /**
     * 
     * @return int|null
     */
    public function getTreeRight()
    {
        return $this->treeRight;
    }

    /**
     * @param int $treeRight
     * 
     * @return void
     */
    public function setTreeRight(int $treeRight = null)
    {
        $this->treeRight = $treeRight;
    }

    /**
     * 
     * @return int|null
     */
    public function getTreeLevel()
    {
        return $this->treeLevel;
    }

    /**
     * @param int $treeLevel
     * 
     * @return void
     */
    public function setTreeLevel(int $treeLevel = null)
    {
        $this->treeLevel = $treeLevel;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param SiestaDateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(SiestaDateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param SiestaDateTime $updatedAt
     * 
     * @return void
     */
    public function setUpdatedAt(SiestaDateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusRole|null
     */
    public function get8C606FE3727ACA70(bool $forceReload = false)
    {
        if ($this->8C606FE3727ACA70 === null || $forceReload) {
            $this->8C606FE3727ACA70 = SyliusRoleService::getInstance()->getEntityByPrimaryKey($this->parentId);
        }
        return $this->8C606FE3727ACA70;
    }

    /**
     * @param SyliusRole $entity
     * 
     * @return void
     */
    public function set8C606FE3727ACA70(SyliusRole $entity = null)
    {
        $this->8C606FE3727ACA70 = $entity;
        $this->parentId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusRole $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusRole $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}