<?php


namespace siestaphp\driver\mysqli\installer;

use Codeception\Util\Debug;
use siestaphp\driver\Connection;
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
     * @param Connection $connection
     */
    public static function install(Connection $connection)
    {

        self::installGetColumnDetails($connection);

        self::installSequencer($connection);

        self::installGetForeignKeyDetails($connection);

        self::installGetIndexDetails($connection);
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

    /**
     * @param Connection $connection
     */
    private static function installGetForeignKeyDetails(Connection $connection)
    {
        $connection->execute("DROP procedure IF EXISTS `SIESTA_GET_FOREIGN_KEY_DETAILS`");
        $foreignKey = new File(__DIR__ . "/getForeignKeyDetails.sql");
        $connection->execute($foreignKey->getContents());
    }

    /**
     * @param Connection $connection
     */
    private static function installGetColumnDetails(Connection $connection)
    {
        $connection->execute("DROP procedure IF EXISTS `SIESTA_GET_COLUMN_DETAILS`");
        $columnDetails = new File(__DIR__ . "/getColumnDetails.sql");
        $connection->execute($columnDetails->getContents());
    }

    /**
     * @param Connection $connection
     */
    private static function installGetIndexDetails(Connection $connection)
    {
        //
        $connection->execute("DROP procedure IF EXISTS `SIESTA_GET_INDEX_DETAILS`");
        $indexDetails = new File(__DIR__ . "/getIndexDetails.sql");
        $connection->query($indexDetails->getContents());

    }

}