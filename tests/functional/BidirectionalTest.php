<?php

namespace siestaphp\tests\functional;

use siestaphp\driver\ConnectionFactory;
use siestaphp\tests\functional\bidirectional\gen\Address;
use siestaphp\tests\functional\bidirectional\gen\Customer;

/**
 * Class ReferenceTest
 */
class BidirectionalTest extends SiestaTester
{

    const ASSET_PATH = "/bidirectional";

    const SRC_XML = "/Bidirectional.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall();

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testAssignment()
    {

        $customer = new Customer();
        $customer->setName("Columbo");

        $address = new Address();
        $address->setCity("Berlin");

        $customer->setAddress($address);

        $this->assertSame($address->getId(), $customer->getAddressId(), "Bidirectional IDs not set");
        $this->assertSame($customer->getId(), $address->getCustomerId(), "Bidirectional IDs not set");

        $connection = ConnectionFactory::getInstance()->getConnection();
        $connection->disableForeignKeyChecks();
        $customer->save(true);
        $connection->enableForeignKeyChecks();

        // load customer
        $customer = Customer::getEntityByPrimaryKey(1);
        $this->assertNotNull($customer, "Customer could not be reloaded");

        // check address is available
        $address = $customer->getAddress();
        $this->assertNotNull($address, "Address could be retrieved");
        $this->assertSame($address->getCustomerId(), 1, "IDs are not same");
        $this->assertSame($address->getCity(), "Berlin");

    }

}