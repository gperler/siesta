<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL\MetaData;

use Siesta\Model\Reference;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class ConstraintRule
{
    const REFERENCE_OPTION_CASCADE = "CASCADE";

    const REFERENCE_OPTION_RESTRICT = "RESTRICT";

    const REFERENCE_OPTION_SET_NULL = "SET NULL";

    const REFERENCE_OPTION_NO_ACTION = "NO ACTION";

    const MAPPING = [
        Reference::ON_X_CASCADE => self::REFERENCE_OPTION_CASCADE,
        Reference::ON_X_NONE => self::REFERENCE_OPTION_NO_ACTION,
        Reference::ON_X_RESTRICT => self::REFERENCE_OPTION_RESTRICT,
        Reference::ON_X_SET_NULL => self::REFERENCE_OPTION_SET_NULL
    ];

    /**
     * @param string $option
     *
     * @return string|null
     */
    public static function schemaToMySQL(string $option): ?string
    {
        $option = strtolower($option);
        return ArrayUtil::getFromArray(self::MAPPING, $option);
    }

    /**
     * @param string $option
     *
     * @return string|null
     */
    public static function mySQLToSchema(string $option): ?string
    {
        foreach (self::MAPPING as $schema => $mySQL) {
            if ($option === $mySQL) {
                return $schema;
            }
        }
        return null;
    }

}