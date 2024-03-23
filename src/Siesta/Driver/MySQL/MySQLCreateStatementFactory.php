<?php
declare(strict_types=1);

namespace Siesta\Driver\MySQL;

use Siesta\Database\CreateStatementFactory;
use Siesta\Database\StoredProcedureDefinition;
use Siesta\Driver\MySQL\MetaData\MySQLStoredProcedure;
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
     * @return StoredProcedureDefinition
     */
    public function buildSequencerStoredProcedure(): StoredProcedureDefinition
    {
        $sequencerFile = new File(__DIR__ . "/Sequencer/Sequencer.sql");

        $storedProcedure = new MySQLStoredProcedure(CreateStatementFactory::SEQUENCER_SP_NAME);

        $createStatement = $sequencerFile->getContents();
        $storedProcedure->setCreateProcedureStatement($createStatement);

        $dropStatement = sprintf(self::DROP_SEQUENCER_SP, CreateStatementFactory::SEQUENCER_SP_NAME);
        $storedProcedure->setDropProcedureStatement($dropStatement);
        return $storedProcedure;
    }

    /**
     * @return string
     */
    public function buildSequencerTable(): string
    {
        return sprintf(self::CREATE_SEQUENCE_TABLE, CreateStatementFactory::SEQUENCER_TABLE_NAME);
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function buildCreateTable(Entity $entity): array
    {
        $statementList = [];
        $tableBuilder = new MySQLTableCreator($entity);
        return array_merge($tableBuilder->buildCreateTable(), $statementList);
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function buildCreateDelimitTable(Entity $entity): array
    {
        $statementList = [];
        $tableBuilder = new MySQLTableCreator($entity);
        $statementList[] = $tableBuilder->buildCreateDelimitTable();

        return $statementList;
    }

}