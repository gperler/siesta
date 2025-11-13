<?php

namespace SiestaTest\Functional\MySQL\MigrationFactory;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\MetaData\IndexPartMetaData;
use Siesta\Database\MigrationStatementFactory;
use SiestaTest\TestUtil\DataModelHelper;

class IndexMigrationTest extends Unit
{

    protected function setUp(): void
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    public function testAttributeMigration()
    {

        $dmr = new DataModelHelper();
        $dmr->createSchema(__DIR__ . "/schema/index.test.xml", true);

        $connection = ConnectionFactory::getConnection();
        $metadata = $connection->getDatabaseMetaData();

        $indexTable = $metadata->getTableByName("IndexTest");
        $this->assertNotNull($indexTable);

        $index = $indexTable->getIndexByName("index");
        $this->assertNotNull($index);

        $factory = $connection->getMigrationStatementFactory();

        // drop foreign key
        $statementList = $factory->createDropIndexStatement($index);
        $statement = $this->postProcessStatement($statementList, "IndexTest");

        $this->assertSame("ALTER TABLE `IndexTest` DROP INDEX `index`", $statement);
        $connection->execute($statement);

        // check if drop worked
        $metadata->refresh();
        $indexTable = $metadata->getTableByName("IndexTest");
        $this->assertNotNull($indexTable);
        $index = $indexTable->getIndexByName("IndexTest");
        $this->assertNull($index);

        // get index to add it
        $datamodel = $dmr->readModel(__DIR__ . "/schema/index.add.test.xml");
        $indexTable = $datamodel->getEntityByTableName("IndexTest");
        $this->assertNotNull($indexTable);
        $newIndex = $indexTable->getIndexByName("indexNew");
        $this->assertNotNull($newIndex);

        // add reference
        $statementList = $factory->createAddIndexStatement($newIndex);
        $statement = $this->postProcessStatement($statementList, "IndexTest");
        $this->assertSame("ALTER TABLE `IndexTest` ADD UNIQUE INDEX `indexNew` USING btree (`string` (20) ASC, `int` ASC)", $statement);
        $connection->execute($statement);

        // check if add reference worked
        $metadata->refresh();
        $indexTable = $metadata->getTableByName("IndexTest");
        $this->assertNotNull($indexTable);
        $index = $indexTable->getIndexByName("indexNew");
        $this->assertNotNull($index);

        $this->assertSame("btree", $index->getType());
        $this->assertSame(true, $index->getIsUnique());

        $indexPartList = $index->getIndexPartList();
        $this->assertSame(2, sizeof($indexPartList));

        $stringColumn = $this->getIndexByColumnName($indexPartList, "string");
        $this->assertNotNull($stringColumn);
        $this->assertSame(20, $stringColumn->getLength());

        $intColumn = $this->getIndexByColumnName($indexPartList, "int");
        $this->assertNotNull($intColumn);
        $this->assertNull($intColumn->getLength());

    }

    /**
     * @param IndexPartMetaData[] $indexPartList
     * @param string $columnName
     *
     * @return IndexPartMetaData|null
     */
    protected function getIndexByColumnName(array $indexPartList, string $columnName)
    {
        foreach ($indexPartList as $indexPart) {
            if ($indexPart->getColumnName() === $columnName) {
                return $indexPart;
            }
        }
        return null;
    }

    /**
     * @param array $statementList
     * @param string $tableName
     *
     * @return string
     */
    public function postProcessStatement(array $statementList, string $tableName): string
    {
        return str_replace(MigrationStatementFactory::TABLE_PLACE_HOLDER, $tableName, $statementList[0]);
    }

}