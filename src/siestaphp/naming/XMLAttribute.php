<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.06.15
 * Time: 20:33
 */

namespace siestaphp\naming;

/**
 * Class XMLAttribute
 * @package siestaphp\datamodel\xml
 */
class XMLAttribute
{

    const ELEMENT_ATTRIBUTE_LIST_NAME = "attributeList";

    const ELEMENT_ATTRIBUTE_NAME = "attribute";

    const ATTRIBUTE_NAME = "name";

    const ATTRIBUTE_TYPE = "type";

    const ATTRIBUTE_DATABASE_NAME = "dbName";

    const ATTRIBUTE_DATABASE_TYPE = "dbType";

    const ATTRIBUTE_DATABASE_LENGTH = "length";

    const ATTRIBUTE_UNIQUE = "unique";

    const ATTRIBUTE_PRIMARY_KEY = "primaryKey";

    const ATTRIBUTE_DEFAULT_VALUE = "defaultValue";

    const ATTRIBUTE_AUTO_VALUE = "autoValue";

    const ATTRIBUTE_REQUIRED = "required";

    /* derived values */

    const ATTRIBUTE_METHOD_NAME = "methodName";

}