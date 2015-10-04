<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 23.06.15
 * Time: 16:09
 */

namespace siestaphp\naming;

/**
 * Class StoredProcedureNaming
 * @package siestaphp\naming
 */
class StoredProcedureNaming
{

    const FIND_BY_PRIMARY_KEY_SUFFIX = "_FIND_BY_PRIMARY_KEY";

    const FIND_BY_REFERENCE = "_FIND_BY_REFERENCE_";

    const DELETE_BY_REFERENCE = "_DELETE_BY_REFERENCE_";

    const DELETE_BY_PRIMARY_KEY_SUFFIX = "_DELETE_BY_PRIMARY_KEY";

    const UPDATE_SUFFIX = "_UPDATE";

    const INSERT_SUFFIX = "_INSERT";

    /**
     * calculates the name of the find by primary key stored procedure
     *
     * @param string $tableName
     *
     * @return string
     */
    public static function getSPFindByPrimaryKeyName($tableName)
    {
        return $tableName . self::FIND_BY_PRIMARY_KEY_SUFFIX;
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
        return $tableName . self::DELETE_BY_PRIMARY_KEY_SUFFIX;
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
        return $tableName . self::FIND_BY_REFERENCE . $referenceName;
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
        return $tableName . self::DELETE_BY_REFERENCE . $referenceName;
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
        return $tableName . self::UPDATE_SUFFIX;
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
        return $tableName . self::INSERT_SUFFIX;
    }

}