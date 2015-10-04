<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 25.09.15
 * Time: 16:46
 */

namespace siestaphp\driver\mysqli\sequencer;


use siestaphp\driver\Driver;
use siestaphp\util\File;

/**
 * Installs the sequencer including table and stored procedure to get next sequence
 *
 * Class SequencerCreator
 * @package siestaphp\driver\mysqli\sequencer
 */
class SequencerCreator {

    /**
     * @param Driver $driver
     */
    public static function install(Driver $driver) {

        // create sequence table
        $sequenceTable = "CREATE TABLE IF NOT EXISTS `SEQUENCER` (`TECHNICALNAME` VARCHAR(120) NOT NULL PRIMARY KEY, `SEQ` INT  ) ENGINE = InnoDB;";
        $driver->query ( $sequenceTable );

        // create sequencer procedure
        $driver->execute("DROP PROCEDURE IF EXISTS SEQUENCER_GETSEQUENCE");
        $sequencerFile = new File(__DIR__ ."/Sequencer.sql");
        $driver->query($sequencerFile->getContents());

    }
}