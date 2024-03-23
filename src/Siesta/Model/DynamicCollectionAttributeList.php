<?php

declare(strict_types=1);

namespace Siesta\Model;

class DynamicCollectionAttributeList
{

    /**
     * @param Entity $entity
     *
     * @return Attribute[]
     */
    public static function getDynamicCollectionAttributeList(Entity $entity): array
    {
        $attributeList = [];

        $tableAttribute = new Attribute($entity);
        $tableAttribute->setPhpName("_foreignTable");
        $tableAttribute->setPhpType("string");
        $tableAttribute->setDbType("VARCHAR(36)");
        $tableAttribute->setIsRequired(true);
        $attributeList[] = $tableAttribute;


        $foreignNameAttribute = new Attribute($entity);
        $foreignNameAttribute->setPhpName("_foreignName");
        $foreignNameAttribute->setPhpType("string");
        $foreignNameAttribute->setDbType("VARCHAR(36)");
        $foreignNameAttribute->setIsRequired(true);
        $attributeList[] = $foreignNameAttribute;

        $foreignIdAttribute = new Attribute($entity);
        $foreignIdAttribute->setPhpName("_foreignId");
        $foreignIdAttribute->setPhpType("string");
        $foreignIdAttribute->setDbType("VARCHAR(36)");
        $foreignIdAttribute->setIsRequired(true);
        $attributeList[] = $foreignIdAttribute;

        return $attributeList;
    }
}