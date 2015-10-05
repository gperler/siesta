<?php

namespace siestaphp\naming;

/**
 * Class XMLReference
 * @package siestaphp\naming
 */
class XMLReference
{

    const ELEMENT_REFERENCE_LIST_NAME = "referenceList";

    const ELEMENT_REFERENCE_NAME = "reference";

    const ATTRIBUTE_NAME = "name";

    const ATTRIBUTE_RELATION_NAME = "relationName";

    const ATTRIBUTE_FOREIGN_CLASS = "foreignClass";

    const ATTRIBUTE_FOREIGN_ATTRIBUTE = "foreignAttribute";

    const ATTRIBUTE_REQUIRED = "required";

    const ATTRIBUTE_ON_DELETE = "onDelete";

    const ATTRIBUTE_ON_UPDATE = "onUpdate";

    /* derived data here */

    // derived values here
    const ATTRIBUTE_METHOD_NAME = "methodName";

    const ATTRIBUTE_FOREIGN_CONSTRUCT_CLASS = "foreignConstructClass";

    const ATTRIBUTE_FOREIGN_METHOD_NAME = "foreignMethodName";

    const ATTRIBUTE_FOREIGN_KEY_TYPE = "foreignKeyType";

    const ATTRIBUTE_SP_FINDER_NAME = "spFinderName";

    const ATTRIBUTE_SP_DELETER_NAME = "spDeleterName";

    const ATTRIBUTE_SP_REFERENCE_CREATOR_NEEDED = "referenceCretorNeeded";

    const ELEMENT_REFERENCED_COLUMN_LIST = "columnList";
    const ELEMENT_COLUMN = "column";

    const ATTRIBUTE_COLUMN_NAME = "name";

    const ATTRIBUTE_COLUMN_PHPTYPE = "type";

    const ATTRIBUTE_COLUMN_METHODNAME = "methodName";

    const ATTRIBUTE_COLUMN_DATABASE_NAME = "databaseName";
}