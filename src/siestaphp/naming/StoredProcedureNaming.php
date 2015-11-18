<?php

namespace siestaphp\naming;

use siestaphp\datamodel\collector\NMMapping;

/**
 * Class StoredProcedureNaming
 * @package siestaphp\naming
 */
class StoredProcedureNaming
{

    const FIND_BY_PRIMARY_KEY_SUFFIX = "_FBPK";

    const FIND_BY_PRIMARY_KEY_DELIMIT = "_FBPK_D";

    const FIND_BY_REFERENCE = "_FBR_";

    const FIND_BY_COLLECTOR = "_FBC_";

    const DELETE_BY_REFERENCE = "_DBR_";

    const DELETE_BY_PRIMARY_KEY_SUFFIX = "_DBPK";

    const UPDATE_SUFFIX = "_U";

    const INSERT_SUFFIX = "_I";



    /**
     * @var StoredProcedureNaming
     */
    private static $instance;

    /**
     * @return StoredProcedureNaming
     */
    public static function GI()
    {
        if (!self::$instance) {
            self::$instance = new StoredProcedureNaming();
        }
        return self::$instance;
    }

    /**
     * calculates the name of the find by primary key stored procedure
     *
     * @param string $tableName
     *
     * @return string
     */
    public static function getSPFindByPrimaryKeyName($tableName)
    {
        return self::GI()->getUniqueName($tableName . self::FIND_BY_PRIMARY_KEY_SUFFIX);
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    public static function getSPFindByPrimaryKeyDelimitName($tableName) {
        return self::GI()->getUniqueName($tableName . self::FIND_BY_PRIMARY_KEY_DELIMIT);
    }

    /**
     * @param NMMapping $nmMapping
     *
     * @return string
     */
    public static function getSPJoinCollectorNMName(NMMapping $nmMapping) {
        $name = $nmMapping->entity->getTable() . "_" . $nmMapping->collector->getName() . "_" . $nmMapping->mappingEntity->getTable();

        return self::GI()->getUniqueName($name);
    }

    /**
     * calculates the name of the delete by primary stored procedure
     *
     * @param string $tableName
     *
     * @return string
     */
    public static function getSPDeleteByPrimaryKeyName($tableName)
    {
        return self::GI()->getUniqueName($tableName . self::DELETE_BY_PRIMARY_KEY_SUFFIX);
    }

    /**
     * calculates the name of a reference finder (looks for foreign keys)
     *
     * @param string $tableName
     * @param string $referenceName
     *
     * @return string
     */
    public static function getSPFindByReferenceName($tableName, $referenceName)
    {
        return self::GI()->getUniqueName($tableName . self::FIND_BY_REFERENCE . $referenceName);
    }

    /**
     * @param string $tableName
     * @param string $referenceName
     * @param string $filerName
     *
     * @return string
     */
    public static function getSPFinByCollectorFilterName($tableName, $referenceName, $filerName) {
        return self::GI()->getUniqueName($tableName . self::FIND_BY_COLLECTOR . $referenceName . $filerName);
    }

    /**
     * calculates the name of a reference deleter (looks for foreign keys)
     *
     * @param string $tableName
     * @param string $referenceName
     *
     * @return string
     */
    public static function getSPDeleteByReferenceName($tableName, $referenceName)
    {
        return self::GI()->getUniqueName($tableName . self::DELETE_BY_REFERENCE . $referenceName);
    }

    /**
     * gets the name of the update procedure
     *
     * @param string $tableName
     *
     * @return string
     */
    public static function getSPUpdateName($tableName)
    {
        return self::GI()->getUniqueName($tableName . self::UPDATE_SUFFIX);
    }

    /**
     * gets the name of the insert procedure
     *
     * @param string $tableName
     *
     * @return string
     */
    public static function getSPInsertName($tableName)
    {
        return self::GI()->getUniqueName($tableName . self::INSERT_SUFFIX);
    }

    /**
     * @var NameMapping[]
     */
    protected $mappingList;

    /**
     *
     */
    public function __construct()
    {
        $this->mappingList = [];
    }

    /**
     * @param string $original
     *
     * @return string
     */
    public function getUniqueName($original)
    {
        // check if the name is already available
        $mappedName = $this->getMappedName($original);
        if ($mappedName) {
            return $mappedName;
        }

        // check if it is unique and short enough
        if ($this->isUnique($original) and $this->isShortEnough($original)) {
            $this->addName($original, $original);
            return $original;
        }

        // create a new unique name
        return $this->createUniqueShortName($original);

    }

    /**
     * @param $original
     *
     * @return null|string
     */
    private function getMappedName($original)
    {
        foreach ($this->mappingList as $mapping) {
            if ($mapping->originalName === $original) {
                return $mapping->shortenedName;
            }
        }
        return null;
    }

    /**
     * @param string $original
     *
     * @return bool
     */
    private function isUnique($original)
    {
        foreach ($this->mappingList as $mapping) {
            if ($mapping->shortenedName === $original) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $original
     *
     * @return string
     */
    private function createUniqueShortName($original)
    {
        $newName = substr($original, 0, 48);
        if ($this->isUnique($newName)) {
            $this->addName($original, $newName);
            return $newName;
        }

        for ($i = 0; $i < 100; $i++) {
            if ($this->isUnique($newName . $i)) {
                $this->addName($original, $newName . $i);
                return $newName . $i;
            }
        }

        $newName = "SP" . md5($original);
        $this->addName($original, $newName);
        return $newName;

    }

    /**
     * @param string $original
     *
     * @return bool
     */
    private function isShortEnough($original)
    {
        return strlen($original) < 50;
    }

    /**
     * @param $original
     * @param $shortenedName
     */
    private function addName($original, $shortenedName)
    {
        $this->mappingList[] = new NameMapping($original, $shortenedName);
    }

}

/**
 * Class NameMapping
 * @package siestaphp\naming
 */
class NameMapping
{

    /**
     * @var string
     */
    public $originalName;

    /**
     * @var string
     */
    public $shortenedName;

    /**
     * @param string $orginal
     * @param string $shortened
     */
    public function __construct($orginal, $shortened)
    {
        $this->originalName = $orginal;
        $this->shortenedName = $shortened;
    }

}