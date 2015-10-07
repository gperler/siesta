<?php

namespace siestaphp\generator;

/**
 * Interface GeneratorLog
 * @package siestaphp\generator
 */
interface GeneratorLog
{
    /**
     * @return bool
     */
    public function hasErrors();

    /**
     *
     */
    public function printValidationSummary();

    /**
     * @param string $text
     */
    public function info($text);

    /**
     * @param string $text
     * @param int $code
     */
    public function warn($text, $code);

    /**
     * @param string $text
     * @param int $code
     */
    public function error($text, $code);

    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     * @param int $code
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName, $code);

    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     * @param int $code
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName,  $code);
}