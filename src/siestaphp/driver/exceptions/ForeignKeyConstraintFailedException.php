<?php

namespace siestaphp\driver\exceptions;

use siestaphp\exceptions\SiestaException;

/**
 * Class TableAlreadyExistsException
 * @package siestaphp\driver\exceptions
 */
class ForeignKeyConstraintFailedException extends SQLException implements SiestaException
{

}

