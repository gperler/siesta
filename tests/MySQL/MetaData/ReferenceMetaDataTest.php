<?php

namespace SiestaTest\Functional\MySQL\MetaData;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;
use SiestaTest\TestUtil\DataModelHelper;

class ReferenceMetaDataTest extends Unit
{

    protected function setUp(): void
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    public function testReferenceMetaData()
    {
        $dmr = new DataModelHelper();

        $validationLogger = $dmr->getValidationLogger(true);
        $validator = $dmr->getValidator(true);
        $model = $dmr->readModel(__DIR__ . "/schema/reference.test.xml");

        $validator->validateDataModel($model, $validationLogger);
        $this->assertFalse($validationLogger->hasError());

        $connection = ConnectionFactory::getConnection();
        $factory = $connection->getCreateStatementFactory();
        $this->assertNotNull($factory);

        $queryPersonList = $factory->buildCreateTable($model->getEntityByTableName("Person"));
        $connection->execute($queryPersonList[0]);
        $queryAddressList = $factory->buildCreateTable($model->getEntityByTableName("Address"));
        $connection->execute($queryAddressList[0]);

        $metadata = $connection->getDatabaseMetaData();
        $addressMetaData = $metadata->getTableByName("Address");

        $constraintMetaDataList = $addressMetaData->getConstraintList();
        $this->assertSame(1, sizeof($constraintMetaDataList));

        $constraint = $constraintMetaDataList["Address_person"];

        $this->assertSame("person", $constraint->getName());
        $this->assertSame("Person", $constraint->getForeignTable());
        $this->assertSame("cascade", $constraint->getOnUpdate());
        $this->assertSame("set null", $constraint->getOnDelete());

        $constraintMappingList = $constraint->getConstraintMappingList();

        $this->assertSame(2, sizeof($constraintMappingList));

        $foreignColumnList = [
            "id",
            "name"
        ];

        foreach ($constraintMappingList as $constraintMapping) {
            $foreignColumn = $constraintMapping->getForeignColumn();
            $this->assertTrue(in_array($foreignColumn, $foreignColumnList));
            if ($foreignColumn === 'id') {
                $this->assertSame("fk_artist", $constraintMapping->getLocalColumn());
            } else {
                $this->assertSame("fk_name", $constraintMapping->getLocalColumn());

            }

        }

    }

}