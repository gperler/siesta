<?php

namespace siestaphp\naming;

/**
 * Class XMLStoredProcedure
 * @package siestaphp\naming
 */
class XMLStoredProcedure
{

    const ELEMENT_STORED_PROCEDURE_LIST = "storedProcedureList";

    const ELEMENT_STORED_PROCEDURE = "storedProcedure";

    const ATTRIBUTE_NAME = "name";

    const ATTRIBUTE_FILE = "file";

    const ATTRIBUTE_MODIFIES = "modifies";

    const ATTRIBUTE_RESULT_TYPE = "resultType";

    /* derived data */
    const ATTRIBUTE_DATABASE_NAME = "databaseName";

    // <parameter name="" spName="" type="" dbType="" referenceType/>

    const ELEMENT_PARAMETER = "parameter";

    const ATTRIBUTE_PARAMETER_NAME = "name";

    const ATTRIBUTE_PARAMETER_SP_NAME = "spName";

    const ATTRIBUTE_PARAMETER_TYPE = "type";

    const ATTRIBUTE_PARAMETER_DATABASE_TYPE = "dbType";

    // SQL Statement
    const ELEMENT_SQL = "sql";

}