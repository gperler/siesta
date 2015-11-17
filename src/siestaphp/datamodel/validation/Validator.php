<?php

namespace siestaphp\datamodel\validation;

/**
 * Class Validator
 * @package siestaphp\datamodel\validation
 */
class Validator
{

    /**
     * @param string $className
     *
     * @return bool
     */
    public function isValidClassName($className) {
        return preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $className) === 1;
    }

    /**
     * @param string $namespace
     *
     * @return bool
     */
    public function isValidNamespace($namespace) {
        return preg_match('/^((?:\\\\{1,2}\\w+|\\w+\\\\{1,2})(?:\\w+\\\\{0,2})+)$/', $namespace) === 1;
    }

    /**
     * @param string $memberName
     *
     * @return bool
     */
    public function isValidMemberName($memberName) {
        return preg_match('[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $memberName) === 1;
    }
}