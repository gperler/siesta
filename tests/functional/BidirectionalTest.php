<?php

require_once "SiestaTester.php";

/**
 * Class ReferenceTest
 */
class BidirectionalTest extends \SiestaTester
{

    const DATABASE_NAME = "BIDIRECTIONAL_TEST";

    const ASSET_PATH = "/bidirectional";

    const SRC_XML = "/Bidirectional.test.xml";

    protected function setUp()
    {

        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array(
            "/gen/bidirectional/Address.php",
            "/gen/bidirectional/Customer.php"
        ));

    }

    protected function tearDown()
    {
        $this->dropDatabase();

    }

    public function testAssignment()
    {
        $this->markTestSkipped();

        $customer = new \gen\bidirectional\Customer();
        $customer->setName("Columbo");

        $address = new \gen\bidirectional\Address();
        $address->setCity("Berlin");

        $customer->setAddress($address);

        $this->assertSame($address->getId(), $customer->getAddressId(), "Bidirectional IDs not set");
        $this->assertSame($customer->getId(), $address->getCustomerId(), "Bidirectional IDs not set");

        $customer->save(true);

        // load customer
        $customer = \gen\bidirectional\Customer::getEntityByPrimaryKey(1);
        $this->assertNotNull($customer, "Customer could not be reloaded");

        // check address is available
        $address = $customer->getAddress();
        $this->assertNotNull($address, "Address could be retrieved");
        $this->assertSame($address->getCustomerId(), 1, "IDs are not same");
        $this->assertSame($address->getCity(), "Berlin");

    }

}