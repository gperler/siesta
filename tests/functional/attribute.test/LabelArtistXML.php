<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 13.09.15
 * Time: 16:46
 */
/*
 * this class contains the data that is also available in LabelArtist.test.xml
 */

class LabelArtistXML
{


    /**
     * @return array
     */
    public static function getEntityDefinition()
    {
        return array(
            "name" => "ArtistEntity",
            "namespace" => "tests\\gen\\gen",
            "constructClass" => "Artist",
            "constructNamespace" => "tests\\gen",
            "table" => "ARTIST",
            "delimit" => false,
            "targetPath" => "tests/gen"
        );
    }

    /**
     * @return array
     */
    public static function getEntityTransformerDefinition() {
        return array_merge(
            self::getEntityDefinition(),
            array(
                "dateTimeInUse" => true,
                "hasReferences" => true,
                "hasAttributes" => true,
                "findByPKSignature" => '$id,$int',
                "storedProcedureCallSignature" => "'\$id','\$int'"
            )
        );
    }



    /**
     * @return array
     */
    public static function getAttributeDefinition()
    {
        return array(
            "id" => array(
                "type" => "int",
                "dbName" => "ID",
                "dbType" => "INT",
                "primaryKey" => true,
                "required" => true,
                "addIndex" => true,
                "defaultValue" => "",
                "autoValue" => "autoincrement"
            ),
            "bool" => array(
                "type" => "bool",
                "dbName" => "D_BOOLEAN",
                "dbType" => "SMALLINT",
                "primaryKey" => false,
                "required" => false,
                "addIndex" => false,
                "defaultValue" => "true",
                "autoValue" => ""
            ),
            "int" => array(
                "type" => "int",
                "dbName" => "D_INTEGER",
                "dbType" => "INT",
                "primaryKey" => true,
                "required" => false,
                "addIndex" => false,
                "defaultValue" => "42",
                "autoValue" => "autoincrement"
            ),
            "float" => array(
                "type" => "float",
                "dbName" => "D_FLOAT",
                "dbType" => "FLOAT",
                "primaryKey" => false,
                "required" => false,
                "addIndex" => false,
                "defaultValue" => "42.42",
                "autoValue" => ""
            ),
            "string" => array(
                "type" => "string",
                "dbName" => "D_STRING",
                "dbType" => "VARCHAR(100)",
                "primaryKey" => false,
                "required" => false,
                "addIndex" => true,
                "defaultValue" => "'Traveling Salesman'",
                "autoValue" => ""
            ),
            "dateTime" => array(
                "type" => "DateTime",
                "dbName" => "D_DATETIME",
                "dbType" => "DATETIME",
                "primaryKey" => false,
                "required" => false,
                "addIndex" => false,
                "defaultValue" => "",
                "autoValue" => ""
            )
        );
    }


    /**
     * @return array
     */
    public static function getAttributeTransformerDefinition() {
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
        return array(
            "label" => array(
                "name" => "label",
                "foreignClass" => "LabelEntity",
                "required" => false,
                "onDelete" => "setnull",
                "onUpdate" => "setnull"
            )
        );
    }

    /**
     * @return array
     */
    public static function getReferenceTransformerDefinition() {

        $referenceData = self::getReferenceDefinition();
        $referenceData["label"]["foreignConstructClass"] = "Label";
        $referenceData["label"]["columnList"] = array(
            "id" => array(
                "type" => "int",
                "methodName" => "Id",
                "databaseName" => "FK_LABEL_ID"
            ),
            "bool" => array(
                "type" => "bool",
                "methodName" => "Bool",
                "databaseName" => "FK_LABEL_D_BOOLEAN"
            )

        );
        return $referenceData;
    }
}