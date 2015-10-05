<?php


namespace siestaphp\driver\mysqli\installer;

use Codeception\Util\Debug;
use siestaphp\driver\Driver;
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
     * @param Driver $driver
     */
    public static function install(Driver $driver)
    {

        self::installGetColumnDetails($driver);

        self::installSequencer($driver);

        self::installGetForeignKeyDetails($driver);

        self::installGetIndexDetails($driver);
    }

    /**
     * @param Driver $driver
     */
    private static function installSequencer(Driver $driver)
    {
        // create sequence table
        $driver->query(self::CREATE_SEQUENCE_TABLE);

        // create installer procedure
        $driver->execute("DROP PROCEDURE IF EXISTS SEQUENCER_GETSEQUENCE");
        $sequencerFile = new File(__DIR__ . "/Sequencer.sql");
        $driver->execute($sequencerFile->getContents());

    }

    /**
     * @param Driver $driver
     */
    private static function installGetForeignKeyDetails(Driver $driver)
    {
        $driver->execute("DROP procedure IF EXISTS `SIESTA_GET_FOREIGN_KEY_DETAILS`");
        $foreignKey = new File(__DIR__ . "/getForeignKeyDetails.sql");
        $driver->execute($foreignKey->getContents());
    }

    /**
     * @param Driver $driver
     */
    private static function installGetColumnDetails(Driver $driver)
    {
        $driver->execute("DROP procedure IF EXISTS `SIESTA_GET_COLUMN_DETAILS`");
        $columnDetails = new File(__DIR__ . "/getColumnDetails.sql");
        $driver->execute($columnDetails->getContents());
    }

    /**
     * @param Driver $driver
     */
    private static function installGetIndexDetails(Driver $driver)
    {
        //
        $driver->execute("DROP procedure IF EXISTS `SIESTA_GET_INDEX_DETAILS`");
        $indexDetails = new File(__DIR__ . "/getIndexDetails.sql");
        $driver->query($indexDetails->getContents());

    }

}