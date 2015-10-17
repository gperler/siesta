<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\generator\GeneratorLog;

/**
 * Class CodeceptionLogger
 * @package siestaphp\tests\functional\xmlreader
 */
class CodeceptionLogger implements GeneratorLog
{
    /**
     * @var int[]
     */
    protected $errorCodeList;

    /**
     * @var int[]
     */
    protected $warningCodeList;

    /**
     *
     */
    public function __construct()
    {
        $this->errorCodeList = array();
        $this->warningCodeList = array();
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return sizeof($this->errorCodeList) !== 0;
    }

    /**
     *
     */
    public function printValidationSummary()
    {
        if (sizeof($this->errorCodeList) !== 0 ) {
            Debug::debug("Errorlist " . implode(",", $this->errorCodeList));
        }

    }

    /**
     * @param string $text
     */
    public function info($text)
    {
        Debug::debug($text);
    }

    /**
     * @param string $text
     * @param int $code
     */
    public function warn($text, $code)
    {
        $this->warningCodeList[] = $code;
        Debug::debug($text);
    }

    /**
     * @param string $text
     * @param int $code
     */
    public function error($text, $code)
    {
        $this->errorCodeList[] = $code;
        Debug::debug($text);
    }

    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     * @param int $code
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName, $code)
    {
        if (!$needle) {
            $this->errorCodeList[] = $code;
            Debug::debug($needle . "$elementName not set");
        }
    }

    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     * @param int $code
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName, $code)
    {
        if (!in_array($needle, $haystack)) {
            $this->errorCodeList[] = $code;
            Debug::debug($needle . "$needle not in list");
        }
    }

    /**
     * @param $errorCode
     * @return bool
     */
    public function isErrorCodeSet($errorCode) {
        return in_array($this->errorCodeList, $errorCode);
    }

}