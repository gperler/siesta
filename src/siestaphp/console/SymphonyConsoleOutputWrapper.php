<?php


namespace siestaphp\console;

use siestaphp\generator\GeneratorLog;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GeneratorConsoleLog
 * @package siestaphp\generator
 */
class SymphonyConsoleOutputWrapper implements GeneratorLog
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
     * @var OutputInterface
     */
    protected $outputInterfase;



    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->errorCount = 0;
        $this->warningCount = 0;
        $this->outputInterfase = $output;
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
        $this->outputInterfase->writeln($text);
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
        if ($this->outputInterfase->getVerbosity() < 3) {
            return;
        }
        $this->println($text);
    }

    /**
     * @param string $text
     * @param int $errorCode
     */
    public function warn($text, $errorCode)
    {
        $this->warningCount++;
        if ($this->outputInterfase->getVerbosity() < 2) {
            return;
        }
        $this->println("[WARN] " . $text);
    }

    /**
     * @param string $text
     * @param int $errorCode
     */
    public function error($text, $errorCode)
    {
        $this->errorCount++;
        $this->println("[ERROR] " . $text);
    }

    /**
     * @param string $needle
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfAttributeNotSet($needle, $attributeName, $elementName, $errorCode)
    {
        if (!$needle) {
            $this->error("Mandatory attribute $attributeName in element <$elementName> not set", $errorCode);

        }
    }

    /**
     * @param string $needle
     * @param array $haystack
     * @param string $attributeName
     * @param string $elementName
     * @param int $errorCode
     */
    public function errorIfNotInList($needle, $haystack, $attributeName, $elementName, $errorCode)
    {
        if (!in_array($needle, $haystack)) {
            $allowedValues = implode(",", $haystack);
            $this->error("Attribute '$attributeName' in element <$elementName> has an invalid value ('$needle'). Allowed values are: $allowedValues", $errorCode);
        }
    }

}