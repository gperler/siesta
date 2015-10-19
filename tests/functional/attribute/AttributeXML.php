<?php

namespace siestaphp\tests\functional\attribute;

/**
 * Class AttributeXML
 * @package siestaphp\tests\funct\attribute
 */
class AttributeXML
{

    /**
     * @return array
     */
    public static function getDefaultValues()
    {
        return array("id" => null, "bool" => true, "int" => 42, "float" => 42.42, "string" => 'Traveling Salesman', "dateTime" => \siestaphp\runtime\Factory::newDateTime('19-08-1977 10:10:10'));
    }

}