<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 23.06.15
 * Time: 20:56
 */

namespace siestaphp\generator;

use Codeception\Util\Debug;

/**
 * Class GeneratorConsoleLog
 * @package siestaphp\generator
 */
class GeneratorConsoleLog implements GeneratorLog
{

    /**
     * counts the errors
     * @var int
     */
    protected $errorCount;

    /**
     * counts the warnings
     * @var int
     */
    protected $warningCount;


    /**
     *
     */
    public function __construct()
    {
        $this->errorCount = 0;
        $this->warningCount = 0;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return $this->errorCount !== 0;
    }

    /**
     * @param string $text
     */
    private function println($text)
    {
        echo $text . PHP_EOL;
    }

    /**
     *
     */
    public function printValidationSummary()
    {
        $this->println($this->errorCount . " error(s)");
        $this->println($this->warningCount . " warning(s)");
    }

    /**
     * @param string $text
     */
    public function info($text)
    {
        $this->println($text);
    }

    /**
     * @param $text
     */
    public function warn($text)
    {
        $this->warningCount++;
        $this->println("[WARN] " . $text);
    }

    /**
     * @param $text
     */
    public function error($text)
    {
        $this->errorCount++;
        $this->println("[ERROR] " . $text);
    }


    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName)
    {
        if (!$needle) {
            $this->error("Mandatory attribute $attributeName in element <$elementName> not set");

        }
    }


    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName)
    {
        if (!in_array($needle, $haystack)) {
            $allowedValues = implode(",", $haystack);
            $this->error("Attribute '$attributeName' in element <$elementName> has an invalid value ('$needle'). Allowed values are: $allowedValues");
        }
    }


}