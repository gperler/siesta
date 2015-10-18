<?php


namespace siestaphp\driver\mysqli\installer;

use siestaphp\driver\Connection;
use siestaphp\util\File;

/**
 * Installs the installer including table and stored procedure to get next sequence
 * Class SequencerCreator
 * @package siestaphp\driver\mysqli\installer
 */
class Installer
{

    const CREATE_SEQUENCE_TABLE = "CREATE TABLE IF NOT EXISTS `SEQUENCER` (`TECHNICALNAME` VARCHAR(120) NOT NULL PRIMARY KEY, `SEQ` INT  ) ENGINE = InnoDB;";

    /**
     * @param Connection $connection
     */
    public static function install(Connection $connection)
    {

        self::installSequencer($connection);
    }

    /**
     * @param Connection $connection
     */
    private static function installSequencer(Connection $connection)
    {
        // create sequence table
        $connection->query(self::CREATE_SEQUENCE_TABLE);

        // create installer procedure
        $connection->execute("DROP PROCEDURE IF EXISTS SEQUENCER_GETSEQUENCE");
        $sequencerFile = new File(__DIR__ . "/Sequencer.sql");
        $connection->execute($sequencerFile->getContents());

    }

}