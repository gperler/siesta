<?php

namespace siestaphp\tests\functional\attribute;

use siestaphp\runtime\SiestaDateTime;

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
        return array("id" => null, "bool" => true, "int" => 42, "float" => 42.42, "string" => 'Traveling Salesman', "dateTime" =>new SiestaDateTime('19-08-1977 10:10:10'));
    }

}