<?php
declare(strict_types=1);

namespace Siesta\Sequencer;

use RuntimeException;
use Siesta\Util\ArrayUtil;

class SequencerFactory
{

    const ERROR_SEQUENCER_NOT_REGISTERED = "Sequencer '%s' is not registered.";

    /**
     * @var SequencerFactory
     */
    private static ?SequencerFactory $instance = null;

    /**
     * @return SequencerFactory
     */
    public static function getInstance(): SequencerFactory
    {
        if (self::$instance === null) {
            self::$instance = new SequencerFactory();
        }
        return self::$instance;
    }

    /**
     * @param string $sequencerName
     * @param string $tableName
     * @param string|null $connectionName
     *
     * @return mixed
     */
    public static function nextSequence(string $sequencerName, string $tableName, string $connectionName = null): mixed
    {
        return self::getInstance()->getNextSequence($sequencerName, $tableName, $connectionName);
    }

    /**
     * @param string $name
     * @param Sequencer $sequencer
     */
    public static function registerSequencer(string $name, Sequencer $sequencer): void
    {
        self::getInstance()->addSequencer($name, $sequencer);
    }

    /**
     * @var Sequencer[]
     */
    protected array $sequencerList;

    /**
     * SequenceFactory constructor.
     */
    protected function __construct()
    {
        $this->sequencerList = [];
        $this->sequencerList[UUIDSequencer::NAME] = new UUIDSequencer();
        $this->sequencerList[AutoincrementSequencer::NAME] = new AutoincrementSequencer();
    }

    /**
     * @param string $name
     * @param Sequencer $sequencer
     */
    public function addSequencer(string $name, Sequencer $sequencer): void
    {
        $this->sequencerList[$name] = $sequencer;
    }

    /**
     * @param string $sequencerName
     *
     * @return Sequencer
     */
    protected function getSequencer(string $sequencerName): Sequencer
    {
        $sequencer = ArrayUtil::getFromArray($this->sequencerList, $sequencerName);
        if ($sequencer === null) {
            throw new RuntimeException(sprintf(self::ERROR_SEQUENCER_NOT_REGISTERED, $sequencerName));
        }
        return $sequencer;
    }

    /**
     * @param string $sequencerName
     * @param string $tableName
     * @param string|null $connectionName
     *
     * @return mixed
     */
    public function getNextSequence(string $sequencerName, string $tableName, string $connectionName = null): mixed
    {
        $sequencer = $this->getSequencer($sequencerName);
        return $sequencer->getNextSequence($tableName, $connectionName);
    }

}