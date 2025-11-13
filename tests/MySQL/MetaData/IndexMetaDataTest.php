<?php

namespace SiestaTest\Functional\MySQL\MetaData;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\MetaData\IndexMetaData;
use Siesta\Database\MetaData\IndexPartMetaData;
use SiestaTest\TestUtil\DataModelHelper;

class IndexMetaDataTest extends Unit
{

    protected function setUp(): void
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    /**
     *
     */
    public function testIndexMetaData()
    {
        $dmr = new DataModelHelper();

        $validationLogger = $dmr->getValidationLogger(true);
        $validatior = $dmr->getValidator(true);
        $model = $dmr->readModel(__DIR__ . "/schema/index.test.xml");

        $validatior->validateDataModel($model, $validationLogger);
        $this->assertFalse($validationLogger->hasError());

        $connection = ConnectionFactory::getConnection();
        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);

        // create tables
        $queryPersonList = $factory->buildCreateTable($model->getEntityByTableName("Person"));
        $connection->execute($queryPersonList[0]);
        $queryPersonList = $factory->buildCreateTable($model->getEntityByTableName("IndexTest"));
        $connection->execute($queryPersonList[0]);

        $metadata = $connection->getDatabaseMetaData();
        $indexTest = $metadata->getTableByName("IndexTest");

        $indexList = $indexTest->getIndexList();
        $this->assertSame(2, sizeof($indexList));

        $index1 = $this->getIndexByName($indexList, "index1");
        $this->assertSame("btree", $index1->getType());
        $this->assertSame(true, $index1->getIsUnique());
        $index1PartList = $index1->getIndexPartList();
        $this->assertSame(1, sizeof($index1PartList));

        $index1Part = $index1PartList[0];
        $this->assertSame("string", $index1Part->getColumnName());
        $this->assertSame(10, $index1Part->getLength());

        $index2 = $this->getIndexByName($indexList, "index2");
        $this->assertSame("btree", $index2->getType());
        $this->assertSame(false, $index2->getIsUnique());

        $index2PartList = $index2->getIndexPartList();
        $this->assertSame(2, sizeof($index2PartList));

        $indexPart1 = $this->getIndexPartByColumn($index2PartList, "datetime");
        $this->assertNotNull($indexPart1);
        $this->assertNull($indexPart1->getLength());
        $this->assertSame("ASC", $indexPart1->getSortOrder());

        $indexPart2 = $this->getIndexPartByColumn($index2PartList, "date");
        $this->assertNotNull($indexPart2);
        $this->assertNull($indexPart2->getLength());


    }

    /**
     * @param IndexPartMetaData[] $indexPartList
     * @param string $columnName
     *
     * @return IndexPartMetaData|null
     */
    protected function getIndexPartByColumn(array $indexPartList, string $columnName)
    {
        foreach ($indexPartList as $indexPart) {
            if ($indexPart->getColumnName() === $columnName) {
                return $indexPart;
            }
        }
        return null;
    }

    /**
     * @param IndexMetaData[] $indexList
     * @param string $indexName
     *
     * @return IndexMetaData|null
     */
    protected function getIndexByName(array $indexList, string $indexName)
    {
        foreach ($indexList as $index) {
            if ($index->getName() === $indexName) {
                return $index;
            }
        }
        return null;
    }

}