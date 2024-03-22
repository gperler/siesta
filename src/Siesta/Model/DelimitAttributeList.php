<?php
declare(strict_types=1);

namespace Siesta\Model;

use Siesta\Sequencer\UUIDSequencer;

/**
 * @author Gregor Müller
 */
class DelimitAttributeList
{

    const COLUMN_DELIMIT_ID = "_delimitId";

    const COLUMN_VALID_FROM = "_validFrom";

    const COLUMN_VALID_UNTIL = "_validUntil";

    /**
     * @param Entity $entity
     * @return Attribute[]
     */
    public static function getDelimitAttributes(Entity $entity): array
    {
        return [
            self::getDelimitIDAttribute($entity),
            self::getValidFromAttribute($entity),
            self::getValidUntilAttribute($entity)
        ];
    }

    /**
     * @param Entity $entity
     * @return Attribute
     */
    protected static function getDelimitIDAttribute(Entity $entity): Attribute
    {
        $attribute = new Attribute($entity);
        $attribute->setPhpName(self::COLUMN_DELIMIT_ID);
        $attribute->setPhpType(UUIDSequencer::PHP_TYPE);
        $attribute->setDbType(UUIDSequencer::DB_TYPE);
        $attribute->setIsPrimaryKey(true);
        $attribute->setAutoValue(UUIDSequencer::NAME);
        $attribute->setIsRequired(true);
        return $attribute;
    }

    /**
     * @param Entity $entity
     * @return Attribute
     */
    protected static function getValidFromAttribute(Entity $entity): Attribute
    {
        $attribute = new Attribute($entity);
        $attribute->setPhpName(self::COLUMN_VALID_FROM);
        $attribute->setPhpType(PHPType::SIESTA_DATE_TIME);
        $attribute->setDbType(DBType::DATETIME);
        return $attribute;
    }

    /**
     * @param Entity $entity
     * @return Attribute
     */
    protected static function getValidUntilAttribute(Entity $entity): Attribute
    {
        $attribute = new Attribute($entity);
        $attribute->setPhpName(self::COLUMN_VALID_UNTIL);
        $attribute->setPhpType(PHPType::SIESTA_DATE_TIME);
        $attribute->setDbType(DBType::DATETIME);
        return $attribute;
    }

}