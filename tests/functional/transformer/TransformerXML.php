<?php

namespace siestaphp\tests\functional\transformer;

/**
 * Class LabelArtistXML
 * @package xmlreadertest
 */
class TransformerXML
{

    /**
     * @return array
     */
    public static function getEntityDefinition()
    {
        return array("name" => "ArtistEntity", "namespace" => "tests\\gen\\gen", "constructClass" => "Artist", "constructNamespace" => "tests\\gen", "table" => "ARTIST", "delimit" => false, "targetPath" => "tests/gen", "constructFactory" => "constructFactory", "constructFactoryFqn" => "constructFactoryFqn");
    }

    /**
     * @return array
     */
    public static function getEntityTransformerDefinition()
    {
        return array_merge(self::getEntityDefinition(), array("dateTimeInUse" => true, "hasReferences" => true, "hasAttributes" => true));
    }

    /**
     * @return array
     */
    public static function getAttributeDefinition()
    {
        return array("id" => array("type" => "int", "dbName" => "ID", "dbType" => "INT", "primaryKey" => true, "required" => true, "addIndex" => true, "defaultValue" => "", "autoValue" => "autoincrement", "length" => 0), "bool" => array("type" => "bool", "dbName" => "D_BOOLEAN", "dbType" => "SMALLINT", "primaryKey" => false, "required" => false, "addIndex" => false, "defaultValue" => "true", "autoValue" => "", "length" => 0), "int" => array("type" => "int", "dbName" => "D_INTEGER", "dbType" => "INT", "primaryKey" => true, "required" => true, "addIndex" => false, "defaultValue" => "42", "autoValue" => "autoincrement", "length" => 0), "float" => array("type" => "float", "dbName" => "D_FLOAT", "dbType" => "FLOAT", "primaryKey" => false, "required" => false, "addIndex" => false, "defaultValue" => "42.42", "autoValue" => "", "length" => 0), "string" => array("type" => "string", "dbName" => "D_STRING", "dbType" => "VARCHAR(100)", "primaryKey" => false, "required" => false, "addIndex" => true, "defaultValue" => "'Traveling Salesman'", "autoValue" => "", "length" => 100), "dateTime" => array("type" => "DateTime", "dbName" => "D_DATETIME", "dbType" => "DATETIME", "primaryKey" => false, "required" => false, "addIndex" => false, "defaultValue" => "", "autoValue" => "", "length" => 0));
    }

    /**
     * @return array
     */
    public static function getAttributeTransformerDefinition()
    {
        $attributeTransformerData = self::getAttributeDefinition();

        $attributeTransformerData["id"]["methodName"] = "Id";
        $attributeTransformerData["bool"]["methodName"] = "Bool";
        $attributeTransformerData["int"]["methodName"] = "Int";
        $attributeTransformerData["float"]["methodName"] = "Float";
        $attributeTransformerData["string"]["methodName"] = "String";
        $attributeTransformerData["dateTime"]["methodName"] = "DateTime";

        return $attributeTransformerData;

    }

    /**
     * @return array
     */
    public static function getReferenceDefinition()
    {
        return array("label" => array("name" => "label", "foreignClass" => "LabelEntity", "required" => false, "onDelete" => "set null", "onUpdate" => "set null", "relationName" => "_label_artist_", "primaryKey" => true));
    }

    /**
     * @return array
     */
    public static function getReferenceTransformerDefinition()
    {

        $referenceData = self::getReferenceDefinition();
        $referenceData["label"]["foreignConstructClass"] = "Label";
        $referenceData["label"]["storedProcedureFinderName"] = "ARTIST_FBR_label";
        $referenceData["label"]["columnList"] = array("labelId" => array("type" => "int", "methodName" => "LabelId", "databaseName" => "label_ID"), "labelBool" => array("type" => "bool", "methodName" => "LabelBool", "databaseName" => "label_D_BOOLEAN")

        );
        return $referenceData;
    }

    /**
     * @return array
     */
    public static function getCollectorDefinition()
    {
        return array("name" => array("type" => "type", "foreignClass" => "Label", "referenceName" => "referenceName",));
    }

    /**
     * @return array
     */
    public static function getCollectorTransformerDefinition()
    {
        $collectorData = self::getCollectorDefinition();
        $collectorData["name"]["methodName"] = "Name";
        return $collectorData;
    }

    /**
     * @return array
     */
    public static function getIndexDefinition()
    {
        return array("indexName" => array("unique" => true, "type" => "btree",), "indexName2" => array("unique" => false, "type" => "hash",));
    }

    /**
     * @return array
     */
    public static function getIndexPartDefinition()
    {
        return array("indexName" => array("bool" => array("sortOrder" => "ASC", "length" => "123"), "int" => array("sortOrder" => "ASC", "length" => "123")), "indexName2" => array("float" => array("sortOrder" => "DESC", "length" => "1"), "string" => array("sortOrder" => "DESC", "length" => "2")));
    }

    /**
     * @return array
     */
    public static function getSPDefinition()
    {
        return array("name" => "XYZ", "modifies" => true, "sql" => "SELECT * FROM __TABLE__ WHERE D_STRING = P_TEST;", "mysql-sql" => "SELECT * FROM MYSQL WHERE D_STRING = P_TEST;", "resultType" => "single");
    }

    /**
     * @return array
     */
    public static function getSPParameterDefinition()
    {
        return array("test" => array("spName" => "P_TEST", "type" => "string", "dbType" => "VARCHAR(100)"), "test2" => array("spName" => "P_TEST2", "type" => "int", "dbType" => "INT"));
    }
}