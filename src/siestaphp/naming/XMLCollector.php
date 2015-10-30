<?php

namespace siestaphp\naming;

/**
 * Class XMLCollector
 * @package siestaphp\naming
 */
class XMLCollector
{

    const ELEMENT_COLLECTOR_NAME = "collector";

    const ATTRIBUTE_NAME = "name";

    const ATTRIBUTE_TYPE = "type";

    const ATTRIBUTE_FOREIGN_CLASS = "foreignClass";

    const ATTRIBUTE_REFERENCE_NAME = "referenceName";

    const ATTRIBUTE_MAPPER_CLASS = "mapperClass";

    /* derived values */
    const ATTRIBUTE_METHOD_NAME = "methodName";

    const ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS = "foreignConstructClass";

    const ATTRIBUTE_REFERENCE_METHOD_NAME = "referenceMethodName";

    const ATTRIBUTE_NM_MAPPING_METHOD_NAME = "nmMethodName";

    const ATTRIBUTE_NM_THIS_METHOD_NAME = "nmThisMethodName";

    const ATTRIBUTE_NM_FOREIGN_METHOD_NAME = "nmForeignMethodName";
}