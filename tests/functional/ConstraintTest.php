<?php

use gen\linkrelation\ArtistEntity;
use gen\linkrelation\LabelEntity;

/**
 * Class ReferenceTest
 */
class ConstraintTest extends \SiestaTester
{

    const DATABASE_NAME = "CONSTRAINT_TEST";

    const ASSET_PATH = "/constraint";

    const SRC_XML = "/Constraint.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML, array("/gen/constraint/Address.php", "/gen/constraint/Customer.php"));

    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testRestrictDelete()
    {
        $standardAddress = new \gen\constraint\Address();
        $standardAddress->setCity("Berlin");
        $standardAddress->setStreet("Kastanienallee");

        $customer = new \gen\constraint\Customer();
        $customer->setStandardAddress($standardAddress);
        $customer->save(true);
        try {
            \gen\constraint\Address::deleteEntityByPrimaryKey($standardAddress->getId());
        } catch (\siestaphp\driver\exceptions\ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
    }

    public function testRestrictUpdate()
    {

        $standardAddress = new \gen\constraint\Address();
        $standardAddress->setCity("Berlin");
        $standardAddress->setStreet("Kastanienallee");

        $customer = new \gen\constraint\Customer();
        $customer->setStandardAddress($standardAddress);
        $customer->save(true);

        try {
            // update address id
            $sql = "UPDATE Address SET ID=7 WHERE ID= " . $standardAddress->getId();
            $this->driver->query($sql);
        } catch (\siestaphp\driver\exceptions\ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");

    }

    public function testCascadeDelete()
    {

        $deliveryAddress = new \gen\constraint\Address();
        $deliveryAddress->setCity("Trier");
        $deliveryAddress->setStreet("Nagelstraße");

        $customer = new \gen\constraint\Customer();
        $customer->setDeliveryAddress($deliveryAddress);
        $customer->save(true);

        // delete address
        \gen\constraint\Address::deleteEntityByPrimaryKey($deliveryAddress->getId());

        $customerLoaded = \gen\constraint\Customer::getEntityByPrimaryKey($customer->getId());
        $this->assertNull($customerLoaded, "On delete cascade failed");
    }

    public function testCascadeUpdate()
    {
        $this->driver->enableForeignKeyChecks();
        $deliveryAddress = new \gen\constraint\Address();
        $deliveryAddress->setCity("Trier");
        $deliveryAddress->setStreet("Nagelstraße");

        $customer = new \gen\constraint\Customer();
        $customer->setDeliveryAddress($deliveryAddress);
        $customer->save(true);

        // update address id
        $sql = "UPDATE Address SET ID=7 WHERE ID= " . $deliveryAddress->getId();
        $this->driver->query($sql);

        $customerLoaded = \gen\constraint\Customer::getEntityByPrimaryKey($customer->getId());

        $this->assertSame($customerLoaded->getDeliveryAddressId(), 7, "On update cascade failed");
    }

    public function testSetNullDelete()
    {
        $billingAddress = new \gen\constraint\Address();
        $billingAddress->setStreet("kleiner Mohr Gasse 5");
        $billingAddress->setCity("Heidelberg");

        $customer = new \gen\constraint\Customer();
        $customer->setBillingAddress($billingAddress);
        $customer->save(true);

        \gen\constraint\Address::deleteEntityByPrimaryKey($billingAddress->getId());

        $customerLoaded = \gen\constraint\Customer::getEntityByPrimaryKey($customer->getId());

        $this->assertNull($customerLoaded->getBillingAddressId(), "On delete set null failed");

    }

    public function testSetNullUpdate()
    {
        $billingAddress = new \gen\constraint\Address();
        $billingAddress->setStreet("kleiner Mohr Gasse 5");
        $billingAddress->setCity("Heidelberg");

        $customer = new \gen\constraint\Customer();
        $customer->setBillingAddress($billingAddress);
        $customer->save(true);

        // update address id
        $sql = "UPDATE Address SET ID=7 WHERE ID= " . $billingAddress->getId();
        $this->driver->query($sql);

        $customerLoaded = \gen\constraint\Customer::getEntityByPrimaryKey($customer->getId());

        $this->assertNull($customerLoaded->getBillingAddressId(), "On update set null failed");

    }

    public function testNoActionDelete()
    {

        $holidyAddress = new \gen\constraint\Address();
        $holidyAddress->setStreet("rue de la montagne");
        $holidyAddress->setCity("Les Deux Alpes");

        $customer = new \gen\constraint\Customer();
        $customer->setHolidayAddress($holidyAddress);
        $customer->save(true);

        try {
            \gen\constraint\Address::deleteEntityByPrimaryKey($holidyAddress->getId());
        } catch (\siestaphp\driver\exceptions\ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
    }

    public function testNoActionUpdate()
    {

        $holidyAddress = new \gen\constraint\Address();
        $holidyAddress->setStreet("rue de la montagne");
        $holidyAddress->setCity("Les Deux Alpes");

        $customer = new \gen\constraint\Customer();
        $customer->setHolidayAddress($holidyAddress);
        $customer->save(true);

        try {
            // update address id
            $sql = "UPDATE Address SET ID=7 WHERE ID= " . $holidyAddress->getId();
            $this->driver->query($sql);
        } catch (\siestaphp\driver\exceptions\ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");

    }

}