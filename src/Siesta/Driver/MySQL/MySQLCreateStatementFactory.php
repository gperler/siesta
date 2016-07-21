<?php
declare(strict_types = 1);
namespace Siesta\Driver\MySQL;

use Siesta\Database\CreateStatementFactory;
use Siesta\Model\Entity;
use Siesta\Util\File;

/**
 * @author Gregor Müller
 */
class MySQLCreateStatementFactory implements CreateStatementFactory
{

    const CREATE_SEQUENCE_TABLE = "CREATE TABLE IF NOT EXISTS `%s` (`TECHNICALNAME` VARCHAR(120) NOT NULL PRIMARY KEY, `SEQ` INT  ) ENGINE = InnoDB;";

    const DROP_SEQUENCER_SP = "DROP PROCEDURE IF EXISTS %s";

    /**
     * @return string[]
     */
    public function buildSequencer() : array
    {
        $sequencerFile = new File(__DIR__ . "/Sequencer/Sequencer.sql");
        $statementList = [];
        $statementList[] = sprintf(self::CREATE_SEQUENCE_TABLE, CreateStatementFactory::SEQUENCER_TABLE_NAME);
        $statementList[] = sprintf(self::DROP_SEQUENCER_SP, CreateStatementFactory::SEQUENCER_SP_NAME);
        $statementList[] = preg_replace('/\s\s+/', ' ', $sequencerFile->getContents());
        return $statementList;
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function buildCreateTable(Entity $entity) : array
    {
        $statementList = [];
        $tableBuilder = new MySQLTableCreator($entity);
        $statementList = array_merge($tableBuilder->buildCreateTable(), $statementList);
        return $statementList;
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function buildCreateDelimitTable(Entity $entity) : array
    {
        $statementList = [];
        $tableBuilder = new MySQLTableCreator($entity);
        $statementList[] = $tableBuilder->buildCreateDelimitTable();

        return $statementList;
    }

}