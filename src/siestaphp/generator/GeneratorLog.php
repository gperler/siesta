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
     */
    public function warn($text);

    /**
     * @param string $text
     */
    public function error($text);

    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName);

    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName);
}