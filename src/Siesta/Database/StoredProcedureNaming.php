<?php
declare(strict_types=1);

namespace Siesta\Database;

use Siesta\Model\CollectionMany;
use Siesta\Model\Entity;
use Siesta\Model\Reference;

/**
 * @author Gregor Müller
 */
class StoredProcedureNaming
{

    const SELECT_BY_PRIMARY_KEY = "%s_select_by_pk";

    const SELECT_BY_PRIMARY_KEY_DELIMIT = "_SB_PK_D";

    const SELECT_BY_REFERENCE = "%s_SB_R_%s";

    const FIND_BY_COLLECTOR = "_FB_C_";

    const FIND_BY_DYNAMIC_COLLECTION = "_FB_DC";

    const DELETE_BY_DYNAMIC_COLLECTION = "_DB_DC";

    const DELETE_BY_REFERENCE = "_delete_by_reference_";

    const DELETE_BY_PRIMARY_KEY = "_delete_by_pk";

    const UPDATE_SUFFIX = "_update";

    const INSERT_SUFFIX = "_insert";

    const COPY_TO_MEMORY_SUFFIX = "_copy";

    /**
     * @var StoredProcedureNaming|null
     */
    private static ?StoredProcedureNaming $instance = null;

    /**
     * @return StoredProcedureNaming
     */
    public static function getInstance(): StoredProcedureNaming
    {
        if (self::$instance === null) {
            self::$instance = new StoredProcedureNaming();
        }
        return self::$instance;
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getSPInsertName(Entity $entity): string
    {
        $name = $entity->getTableName() . self::INSERT_SUFFIX;
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getUpdateName(Entity $entity): string
    {
        $name = $entity->getTableName() . self::UPDATE_SUFFIX;
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getSelectByPrimaryKeyName(Entity $entity): string
    {
        $name = sprintf(self::SELECT_BY_PRIMARY_KEY, $entity->getTableName());
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getSelectByPrimaryKeyDelimitName(Entity $entity): string
    {
        $name = $entity->getTableName() . self::SELECT_BY_PRIMARY_KEY_DELIMIT;
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param Entity $entity
     * @param Reference $reference
     *
     * @return string
     */
    public static function getSelectByReferenceName(Entity $entity, Reference $reference): string
    {
        $name = sprintf(self::SELECT_BY_REFERENCE, $entity->getTableName(), $reference->getName());
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getSelectByCollectionManyName(CollectionMany $collectionMany): string
    {
        $name = $collectionMany->getName();
        $foreignEntity = $collectionMany->getForeignEntity();
        $mappingEntity = $collectionMany->getMappingEntity();

        $spName = $foreignEntity->getTableName() . '_S_JOIN_' . $mappingEntity->getTableName() . '_' . $name;
        return self::getInstance()->getUniqueName($spName);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getSelectByDynamicCollectionName(Entity $entity): string
    {
        $spName = $entity->getTableName() . self::FIND_BY_DYNAMIC_COLLECTION;
        return self::getInstance()->getUniqueName($spName);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getDeleteByDynamicCollectionName(Entity $entity): string
    {
        $spName = $entity->getTableName() . self::DELETE_BY_DYNAMIC_COLLECTION;
        return self::getInstance()->getUniqueName($spName);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getDeleteByPrimaryKeyName(Entity $entity): string
    {
        $name = $entity->getTableName() . self::DELETE_BY_PRIMARY_KEY;
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param Entity $entity
     * @param Reference $reference
     *
     * @return string
     */
    public static function getDeleteByReferenceName(Entity $entity, Reference $reference): string
    {
        $name = $entity->getTableName() . self::DELETE_BY_REFERENCE . $reference->getName();
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getDeleteByCollectionManyName(CollectionMany $collectionMany): string
    {
        $name = $collectionMany->getName();
        $foreignEntity = $collectionMany->getForeignEntity();
        $mappingEntity = $collectionMany->getMappingEntity();

        $spName = $foreignEntity->getTableName() . '_D_JOIN_' . $mappingEntity->getTableName() . '_' . $name;
        return self::getInstance()->getUniqueName($spName);
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getDeleteCollectionManyAssignmentName(CollectionMany $collectionMany): string
    {
        $name = $collectionMany->getName();
        $entity = $collectionMany->getEntity();
        $mappingEntity = $collectionMany->getMappingEntity();

        $spName = $mappingEntity->getTableName() . '_D_A_' . $entity->getTableName() . '_' . $name;
        return self::getInstance()->getUniqueName($spName);
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getCopyToMemoryTable(Entity $entity): string
    {
        $name = $entity->getTableName() . self::COPY_TO_MEMORY_SUFFIX;
        return self::getInstance()->getUniqueName($name);
    }

    /**
     * @param string $tableName
     * @param string $referenceName
     * @param string $fileName
     *
     * @return string
     */
    public static function getCollectorFilterName(string $tableName, string $referenceName, string $fileName): string
    {
        return self::getInstance()->getUniqueName($tableName . self::FIND_BY_COLLECTOR . $referenceName . $fileName);
    }

    /**
     * @var NameMapping[]
     */
    protected array $mappingList;

    /**
     *
     */
    private function __construct()
    {
        $this->mappingList = [];
    }

    /**
     * @param string $original
     *
     * @return string
     */
    public function getUniqueName(string $original): string
    {
        // check if the name is already available
        $mappedName = $this->getMappedName($original);
        if ($mappedName !== null) {
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
    private function getMappedName($original): ?string
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
    private function isUnique(string $original): bool
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
    private function createUniqueShortName(string $original): string
    {
        $newName = mb_substr($original, 0, 48);
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
    private function isShortEnough(string $original): bool
    {
        return strlen($original) < 50;
    }

    /**
     * @param $original
     * @param $shortenedName
     */
    private function addName($original, $shortenedName): void
    {
        $this->mappingList[] = new NameMapping($original, $shortenedName);
    }

}

class NameMapping
{

    /**
     * @var string
     */
    public string $originalName;

    /**
     * @var string
     */
    public string $shortenedName;

    /**
     * @param string $original
     * @param string $shortened
     */
    public function __construct(string $original, string $shortened)
    {
        $this->originalName = $original;
        $this->shortenedName = $shortened;
    }

}