<?php

namespace siestaphp\tests\functional;

use siestaphp\driver\exceptions\ForeignKeyConstraintFailedException;
use siestaphp\tests\functional\constraint\gen\Address;
use siestaphp\tests\functional\constraint\gen\AddressService;
use siestaphp\tests\functional\constraint\gen\Customer;
use siestaphp\tests\functional\constraint\gen\CustomerService;

/**
 * Class ReferenceTest
 */
class ConstraintTest extends SiestaTester
{

    const ASSET_PATH = "/constraint";

    const SRC_XML = "/Constraint.test.xml";

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

    public function testRestrictDelete()
    {
        $manager = AddressService::getInstance();
        $standardAddress = new Address();
        $standardAddress->setCity("Berlin");
        $standardAddress->setStreet("Kastanienallee");

        $customer = new Customer();
        $customer->setStandardAddress($standardAddress);
        $customer->save(true);
        try {
            $manager->deleteEntityByPrimaryKey($standardAddress->getId());
            $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
        } catch (ForeignKeyConstraintFailedException $e) {
        }

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
            $this->connection->query($sql);
            $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
        } catch (ForeignKeyConstraintFailedException $e) {
        }

    }

    public function testCascadeDelete()
    {

        $deliveryAddress = new Address();
        $deliveryAddress->setCity("Trier");
        $deliveryAddress->setStreet("Nagelstraße");

        $customer = new Customer();
        $customer->setDeliveryAddress($deliveryAddress);
        $customer->save(true);

        $manager = AddressService::getInstance();

        // delete address
        $manager->deleteEntityByPrimaryKey($deliveryAddress->getId());


        $customerLoaded = CustomerService::getInstance()->getEntityByPrimaryKey($customer->getId());
        $this->assertNull($customerLoaded, "On delete cascade failed");
    }

    public function testCascadeUpdate()
    {
        $this->connection->enableForeignKeyChecks();
        $deliveryAddress = new Address();
        $deliveryAddress->setCity("Trier");
        $deliveryAddress->setStreet("Nagelstraße");

        $customer = new Customer();
        $customer->setDeliveryAddress($deliveryAddress);
        $customer->save(true);

        // update address id
        $sql = "UPDATE Address SET ID=7 WHERE ID= " . $deliveryAddress->getId();
        $this->connection->query($sql);

        $customerLoaded = CustomerService::getInstance()->getEntityByPrimaryKey($customer->getId());

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


        AddressService::getInstance()->deleteEntityByPrimaryKey($billingAddress->getId());

        $customerLoaded = CustomerService::getInstance()->getEntityByPrimaryKey($customer->getId());

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
        $this->connection->query($sql);

        $customerLoaded = CustomerService::getInstance()->getEntityByPrimaryKey($customer->getId());

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
            AddressService::getInstance()->deleteEntityByPrimaryKey($holidyAddress->getId());
            $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
        } catch (ForeignKeyConstraintFailedException $e) {
        }

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
            $this->connection->query($sql);
            $this->assertTrue(false, "Foreign key constraint failed exception not thrown");
        } catch (ForeignKeyConstraintFailedException $e) {
            return;
        }

    }

}