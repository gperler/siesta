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

class sylius_permission implements ArraySerializable
{

    const TABLE_NAME = "sylius_permission";

    const COLUMN_ID = "id";

    const COLUMN_PARENT_ID = "parent_id";

    const COLUMN_CODE = "code";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_TREE_LEFT = "tree_left";

    const COLUMN_TREE_RIGHT = "tree_right";

    const COLUMN_TREE_LEVEL = "tree_level";

    const COLUMN_CREATED_AT = "created_at";

    const COLUMN_UPDATED_AT = "updated_at";

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
    protected $parent_id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var int
     */
    protected $tree_left;

    /**
     * @var int
     */
    protected $tree_right;

    /**
     * @var int
     */
    protected $tree_level;

    /**
     * @var SiestaDateTime
     */
    protected $created_at;

    /**
     * @var SiestaDateTime
     */
    protected $updated_at;

    /**
     * @var sylius_permission
     */
    protected $C5160A4E727ACA70;

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
        $spCall = ($this->_existing) ? "CALL sylius_permission_U(" : "CALL sylius_permission_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->parent_id) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteInt($this->tree_left) . ',' . Escaper::quoteInt($this->tree_right) . ',' . Escaper::quoteInt($this->tree_level) . ',' . Escaper::quoteDateTime($this->created_at) . ',' . Escaper::quoteDateTime($this->updated_at) . ');';
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
        if ($cascade && $this->C5160A4E727ACA70 !== null) {
            $this->C5160A4E727ACA70->save($cascade, $cycleDetector, $connectionName);
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
        $this->parent_id = $resultSet->getIntegerValue("parent_id");
        $this->code = $resultSet->getStringValue("code");
        $this->description = $resultSet->getStringValue("description");
        $this->tree_left = $resultSet->getIntegerValue("tree_left");
        $this->tree_right = $resultSet->getIntegerValue("tree_right");
        $this->tree_level = $resultSet->getIntegerValue("tree_level");
        $this->created_at = $resultSet->getDateTime("created_at");
        $this->updated_at = $resultSet->getDateTime("updated_at");
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
        $connection->execute("CALL sylius_permission_DB_PK($id)");
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
        $this->setParent_id($arrayAccessor->getIntegerValue("parent_id"));
        $this->setCode($arrayAccessor->getStringValue("code"));
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setTree_left($arrayAccessor->getIntegerValue("tree_left"));
        $this->setTree_right($arrayAccessor->getIntegerValue("tree_right"));
        $this->setTree_level($arrayAccessor->getIntegerValue("tree_level"));
        $this->setCreated_at($arrayAccessor->getDateTime("created_at"));
        $this->setUpdated_at($arrayAccessor->getDateTime("updated_at"));
        $this->_existing = ($this->id !== null);
        $C5160A4E727ACA70Array = $arrayAccessor->getArray("C5160A4E727ACA70");
        if ($C5160A4E727ACA70Array !== null) {
            $C5160A4E727ACA70 = sylius_permissionService::getInstance()->newInstance();
            $C5160A4E727ACA70->fromArray($C5160A4E727ACA70Array);
            $this->setC5160A4E727ACA70($C5160A4E727ACA70);
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
            "parent_id" => $this->getParent_id(),
            "code" => $this->getCode(),
            "description" => $this->getDescription(),
            "tree_left" => $this->getTree_left(),
            "tree_right" => $this->getTree_right(),
            "tree_level" => $this->getTree_level(),
            "created_at" => ($this->getCreated_at() !== null) ? $this->getCreated_at()->getJSONDateTime() : null,
            "updated_at" => ($this->getUpdated_at() !== null) ? $this->getUpdated_at()->getJSONDateTime() : null
        ];
        if ($this->C5160A4E727ACA70 !== null) {
            $result["C5160A4E727ACA70"] = $this->C5160A4E727ACA70->toArray($cycleDetector);
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
    public function getParent_id()
    {
        return $this->parent_id;
    }

    /**
     * @param int $parent_id
     * 
     * @return void
     */
    public function setParent_id(int $parent_id = null)
    {
        $this->parent_id = $parent_id;
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
     * @return int|null
     */
    public function getTree_left()
    {
        return $this->tree_left;
    }

    /**
     * @param int $tree_left
     * 
     * @return void
     */
    public function setTree_left(int $tree_left = null)
    {
        $this->tree_left = $tree_left;
    }

    /**
     * 
     * @return int|null
     */
    public function getTree_right()
    {
        return $this->tree_right;
    }

    /**
     * @param int $tree_right
     * 
     * @return void
     */
    public function setTree_right(int $tree_right = null)
    {
        $this->tree_right = $tree_right;
    }

    /**
     * 
     * @return int|null
     */
    public function getTree_level()
    {
        return $this->tree_level;
    }

    /**
     * @param int $tree_level
     * 
     * @return void
     */
    public function setTree_level(int $tree_level = null)
    {
        $this->tree_level = $tree_level;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * @param SiestaDateTime $created_at
     * 
     * @return void
     */
    public function setCreated_at(SiestaDateTime $created_at = null)
    {
        $this->created_at = $created_at;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * @param SiestaDateTime $updated_at
     * 
     * @return void
     */
    public function setUpdated_at(SiestaDateTime $updated_at = null)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_permission|null
     */
    public function getC5160A4E727ACA70(bool $forceReload = false)
    {
        if ($this->C5160A4E727ACA70 === null || $forceReload) {
            $this->C5160A4E727ACA70 = sylius_permissionService::getInstance()->getEntityByPrimaryKey($this->parent_id);
        }
        return $this->C5160A4E727ACA70;
    }

    /**
     * @param sylius_permission $entity
     * 
     * @return void
     */
    public function setC5160A4E727ACA70(sylius_permission $entity = null)
    {
        $this->C5160A4E727ACA70 = $entity;
        $this->parent_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_permission $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_permission $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}