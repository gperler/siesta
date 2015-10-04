<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 27.09.15
 * Time: 11:42
 */

namespace siestaphp\naming;

/**
 * Class XMLCollector
 * @package siestaphp\naming
 */
class XMLCollector
{

    const ELEMENT_COLLECTOR_LIST_NAME = "collectorList";

    const ELEMENT_COLLECTOR_NAME = "collector";

    const ATTRIBUTE_NAME = "name";

    const ATTRIBUTE_TYPE = "type";

    const ATTRIBUTE_FOREIGN_CLASS = "foreignClass";

    const ATTRIBUTE_REFERENCE_NAME = "referenceName";

    /* derived values */
    const ATTRIBUTE_METHOD_NAME = "methodName";

    const ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS = "foreignConstructClass";

    const ATTRIBUTE_REFERENCE_METHOD_NAME = "referenceMethodName";
}