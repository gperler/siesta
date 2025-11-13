<?php

namespace SiestaTest\Functional\MySQL\MigrationFactory;

use Codeception\Test\Unit;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\MetaData\ConstraintMappingMetaData;
use Siesta\Database\MigrationStatementFactory;
use SiestaTest\TestUtil\DataModelHelper;

class ReferenceMigrationTest extends Unit
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
        $dmr->createSchema(__DIR__ . "/schema/reference.test.xml", true);

        $connection = ConnectionFactory::getConnection();
        $metadata = $connection->getDatabaseMetaData();

        $addressTable = $metadata->getTableByName("Address");
        $this->assertNotNull($addressTable);

        $constraint = $addressTable->getConstraintByName("Address_person");
        $this->assertNotNull($constraint);

        $factory = $connection->getMigrationStatementFactory();

        // drop foreign key
        $statementList = $factory->createDropConstraintStatement($constraint);
        $statement = $this->postProcessStatement($statementList, "Address");
        $this->assertSame("ALTER TABLE `Address` DROP FOREIGN KEY Address_person", $statement);
        $connection->execute($statement);

        // check if drop worked
        $metadata->refresh();
        $addressTable = $metadata->getTableByName("Address");
        $this->assertNotNull($addressTable);
        $constraint = $addressTable->getConstraintByName("Address_person");
        $this->assertNull($constraint);

        // get Reference to add it
        $datamodel = $dmr->readModel(__DIR__ . "/schema/reference.add.test.xml");
        $addressTable = $datamodel->getEntityByTableName("Address");
        $this->assertNotNull($addressTable);
        $reference = $addressTable->getReferenceByName("company");
        $this->assertNotNull($reference);

        // add reference
        $statementList = $factory->createAddReferenceStatement($reference);
        $statement = $this->postProcessStatement($statementList, "Address");
        $connection->execute($statement);
        $this->assertSame("ALTER TABLE `Address` ADD CONSTRAINT `Address_company` FOREIGN KEY (`fk_company`,`fk_name`) REFERENCES `Company` (`id`,`name`) ON DELETE cascade ON UPDATE set null", $statement);

        // check if add reference worked
        $metadata->refresh();
        $addressTable = $metadata->getTableByName("Address");
        $this->assertNotNull($addressTable);
        $constraint = $addressTable->getConstraintByName("Address_company");
        $this->assertNotNull($constraint);

        $this->assertSame("company", $constraint->getName());
        $this->assertSame("Company", $constraint->getForeignTable());
        $this->assertSame("cascade", $constraint->getOnDelete());
        $this->assertSame("set null", $constraint->getOnUpdate());

        $constraintMappingList = $constraint->getConstraintMappingList();
        $this->assertSame(2, sizeof($constraintMappingList));

        $fkCompanyConstraint = $this->getConstraintMappingByLocal($constraintMappingList, "fk_company");
        $this->assertNotNull($fkCompanyConstraint);
        $this->assertSame("id", $fkCompanyConstraint->getForeignColumn());

        $fkNameConstraint = $this->getConstraintMappingByLocal($constraintMappingList, "fk_name");
        $this->assertNotNull($fkNameConstraint);
        $this->assertSame("name", $fkNameConstraint->getForeignColumn());

    }

    /**
     * @param ConstraintMappingMetaData[] $constraintMappingList
     * @param string $local
     *
     * @return ConstraintMappingMetaData|null
     */
    protected function getConstraintMappingByLocal(array $constraintMappingList, string $local)
    {
        foreach ($constraintMappingList as $constraintMapping) {
            if ($constraintMapping->getLocalColumn() === $local) {
                return $constraintMapping;
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