<?php

namespace siestaphp\tests\functional;

use siestaphp\driver\exceptions\ForeignKeyConstraintFailedException;
use siestaphp\tests\functional\constraint\gen\Address;
use siestaphp\tests\functional\constraint\gen\Customer;

/**
 * Class ReferenceTest
 */
class ConstraintTest extends SiestaTester
{

    const DATABASE_NAME = "CONSTRAINT_TEST";

    const ASSET_PATH = "/constraint";

    const SRC_XML = "/Constraint.test.xml";

    protected function setUp()
    {
        $this->connectAndInstall(self::DATABASE_NAME);

        $this->generateEntityFile(self::ASSET_PATH, self::SRC_XML);

        $this->assertNoValidationErrors();

    }

    protected function tearDown()
    {
        $this->dropDatabase();
    }

    public function testRestrictDelete()
    {
        $standardAddress = new Address();
        $standardAddress->setCity("Berlin");
        $standardAddress->setStreet("Kastanienallee");

        $customer = new Customer();
        $customer->setStandardAddress($standardAddress);
        $customer->save(true);
        try {
            Address::deleteEntityByPrimaryKey($standardAddress->getId());
        } catch (ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
    }

    public function testRestrictUpdate()
    {

        $standardAddress = new Address();
        $standardAddress->setCity("Berlin");
        $standardAddress->setStreet("Kastanienallee");

        $customer = new Customer();
        $customer->setStandardAddress($standardAddress);
        $customer->save(true);

        try {
            // update address id
            $sql = "UPDATE Address SET ID=7 WHERE ID= " . $standardAddress->getId();
            $this->driver->query($sql);
        } catch (ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");

    }

    public function testCascadeDelete()
    {

        $deliveryAddress = new Address();
        $deliveryAddress->setCity("Trier");
        $deliveryAddress->setStreet("Nagelstraße");

        $customer = new Customer();
        $customer->setDeliveryAddress($deliveryAddress);
        $customer->save(true);

        // delete address
        Address::deleteEntityByPrimaryKey($deliveryAddress->getId());

        $customerLoaded = Customer::getEntityByPrimaryKey($customer->getId());
        $this->assertNull($customerLoaded, "On delete cascade failed");
    }

    public function testCascadeUpdate()
    {
        $this->driver->enableForeignKeyChecks();
        $deliveryAddress = new Address();
        $deliveryAddress->setCity("Trier");
        $deliveryAddress->setStreet("Nagelstraße");

        $customer = new Customer();
        $customer->setDeliveryAddress($deliveryAddress);
        $customer->save(true);

        // update address id
        $sql = "UPDATE Address SET ID=7 WHERE ID= " . $deliveryAddress->getId();
        $this->driver->query($sql);

        $customerLoaded = Customer::getEntityByPrimaryKey($customer->getId());

        $this->assertSame($customerLoaded->getDeliveryAddressId(), 7, "On update cascade failed");
    }

    public function testSetNullDelete()
    {
        $billingAddress = new Address();
        $billingAddress->setStreet("kleiner Mohr Gasse 5");
        $billingAddress->setCity("Heidelberg");

        $customer = new Customer();
        $customer->setBillingAddress($billingAddress);
        $customer->save(true);

        Address::deleteEntityByPrimaryKey($billingAddress->getId());

        $customerLoaded = Customer::getEntityByPrimaryKey($customer->getId());

        $this->assertNull($customerLoaded->getBillingAddressId(), "On delete set null failed");

    }

    public function testSetNullUpdate()
    {
        $billingAddress = new Address();
        $billingAddress->setStreet("kleiner Mohr Gasse 5");
        $billingAddress->setCity("Heidelberg");

        $customer = new Customer();
        $customer->setBillingAddress($billingAddress);
        $customer->save(true);

        // update address id
        $sql = "UPDATE Address SET ID=7 WHERE ID= " . $billingAddress->getId();
        $this->driver->query($sql);

        $customerLoaded = Customer::getEntityByPrimaryKey($customer->getId());

        $this->assertNull($customerLoaded->getBillingAddressId(), "On update set null failed");

    }

    public function testNoActionDelete()
    {

        $holidyAddress = new Address();
        $holidyAddress->setStreet("rue de la montagne");
        $holidyAddress->setCity("Les Deux Alpes");

        $customer = new Customer();
        $customer->setHolidayAddress($holidyAddress);
        $customer->save(true);

        try {
            Address::deleteEntityByPrimaryKey($holidyAddress->getId());
        } catch (\siestaphp\driver\exceptions\ForeignKeyConstraintFailedException $e) {
            return;
        }
        $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
    }

    public function testNoActionUpdate()
    {

        $holidyAddress = new Address();
        $holidyAddress->setStreet("rue de la montagne");
        $holidyAddress->setCity("Les Deux Alpes");

        $customer = new Customer();
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