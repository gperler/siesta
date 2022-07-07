<?php

namespace SiestaTest\End2End\Collection;

use Siesta\Util\File;
use SiestaTest\End2End\Collection\Entity\Cart;
use SiestaTest\End2End\Collection\Entity\CartItem;
use SiestaTest\End2End\Collection\Generated\CartEntityService;
use SiestaTest\End2End\Collection\Generated\CartItemMPK;
use SiestaTest\End2End\Collection\Generated\CartItemUUID;
use SiestaTest\End2End\Collection\Generated\CartMPK;
use SiestaTest\End2End\Collection\Generated\CartMPKService;
use SiestaTest\End2End\Collection\Generated\CartUUID;
use SiestaTest\End2End\Collection\Generated\CartUUIDService;
use SiestaTest\End2End\Util\End2EndTest;

class CollectionTest extends End2EndTest
{

    public function setUp(): void
    {
        $silent = true;
        $this->deleteGenDir(__DIR__ . "/Generated");
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/collection.test.xml");
        $this->generateSchema($schemaFile, __DIR__, $silent);
    }

    public function tearDown(): void {
    }

    /**
     *
     */
    public function testCollection()
    {

        $cartItem1 = new CartItem();
        $cartItem1->setName("Wayfaring Stranger");

        $cartItem2 = new CartItem();
        $cartItem2->setName("Waterfront");

        $cart = new Cart();
        $cart->setName("Jamie Woon");
        $cart->addToCartItem($cartItem1);
        $cart->addToCartItem($cartItem2);

        $this->assertNotNull($cart->getId());
        $cart->save(true);

        //
        // reload cart
        //
        $cartService = CartEntityService::getInstance();
        $cartReloaded = $cartService->getEntityByPrimaryKey($cart->getId());

        // check items are there
        $cartItemList = $cartReloaded->getCartItem();
        $this->assertSame(2, sizeof($cartItemList));

        $cartItem3 = new CartItem();
        $cartItem3->setName("Middle");
        $cartReloaded->addToCartItem($cartItem3);
        $cartReloaded->save(true);

        $cartReloaded = $cartService->getEntityByPrimaryKey($cart->getId());
        $cartItemList = $cartReloaded->getCartItem();
        $this->assertSame(3, sizeof($cartItemList));

        $cartReloaded->deleteFromCartItem($cartItem1->getId());

        $this->assertSame(2, sizeof($cartReloaded->getCartItem()));
        $this->assertSame(2, sizeof($cartReloaded->getCartItem(true)));



        $cartReloaded->deleteFromCartItem();
        $this->assertSame(0, sizeof($cartReloaded->getCartItem()));
        $this->assertSame(0, sizeof($cartReloaded->getCartItem(true)));



    }

    /**
     *
     */
    public function testCollectionUUID()
    {

        $cart = new CartUUID();
        $cart->setName("Jamie Woon");

        $cartItem = new CartItemUUID();
        $cartItem->setName("Wayfaring Stranger");
        $cart->addToCartItem($cartItem);

        $cartItem = new CartItemUUID();
        $cartItem->setName("Waterfront");
        $cart->addToCartItem($cartItem);

        $this->assertNotNull($cart->getId());

        $cart->save(true);

        $cartService = CartUUIDService::getInstance();
        $cartReloaded = $cartService->getEntityByPrimaryKey($cart->getId());

        $cartItemList = $cartReloaded->getCartItem();
        $this->assertSame(2, sizeof($cartItemList));

        $cartItem = new CartItemUUID();
        $cartItem->setName("Middle");
        $cartReloaded->addToCartItem($cartItem);
        $cartReloaded->save(true);

        $cartReloaded = $cartService->getEntityByPrimaryKey($cart->getId());
        $cartItemList = $cartReloaded->getCartItem();
        $this->assertSame(3, sizeof($cartItemList));

        $cartReloaded->deleteFromCartItem($cartItem->getId());

        $this->assertSame(2, sizeof($cartReloaded->getCartItem()));
        $this->assertSame(2, sizeof($cartReloaded->getCartItem(true)));


        $cartReloaded->deleteFromCartItem();

        $this->assertSame(0, sizeof($cartReloaded->getCartItem()));
        $this->assertSame(0, sizeof($cartReloaded->getCartItem(true)));


    }

    public function testCollectionMPK()
    {
        $cart = new CartMPK();
        $cart->setName("Jamie Woon");

        $cartItem = new CartItemMPK();
        $cartItem->setName("Wayfaring Stranger");
        $cart->addToCartItem($cartItem);

        $cartItem = new CartItemMPK();
        $cartItem->setName("Waterfront");
        $cart->addToCartItem($cartItem);

        $this->assertNotNull($cart->getId1());
        $this->assertNotNull($cart->getId2());
        $cart->save(true);

        $cartService = CartMPKService::getInstance();
        $cartReloaded = $cartService->getEntityByPrimaryKey($cart->getId1(), $cart->getId2());

        $cartItemList = $cartReloaded->getCartItem();
        $this->assertSame(2, sizeof($cartItemList));

        $cartItem = new CartItemMPK();
        $cartItem->setName("Middle");
        $cartReloaded->addToCartItem($cartItem);
        $cartReloaded->save(true);

        $cartReloaded = $cartService->getEntityByPrimaryKey($cart->getId1(), $cart->getId2());
        $cartItemList = $cartReloaded->getCartItem();
        $this->assertSame(3, sizeof($cartItemList));

        $cartReloaded->deleteFromCartItem($cartItem->getId1(), $cartItem->getId2());

        $this->assertSame(2, sizeof($cartReloaded->getCartItem()));
        $this->assertSame(2, sizeof($cartReloaded->getCartItem(true)));

    }

    public function testCollectionFromJSON()
    {
        $json = file_get_contents(__DIR__ . "/schema/collection.json");

        $cart = new CartUUID();
        $cart->fromJSON($json);
        $this->assertNotNull($cart->getId());
        $this->assertSame("Jamie Woon", $cart->getName());

        $cartItemList = $cart->getCartItem();
        $this->assertSame(2, sizeof($cartItemList));

        $this->assertSame("Wayfaring Stranger", $cartItemList[0]->getName());
        $this->assertSame("Waterfront", $cartItemList[1]->getName());
    }

    /**
     *
     */
    public function testCollectionToJSON()
    {
        $cart = new Cart();
        $cart->setName("Jamie Woon");

        $cartItem = new CartItem();
        $cartItem->setName("Wayfaring Stranger");
        $cart->addToCartItem($cartItem);

        $cartItem = new CartItem();
        $cartItem->setName("Waterfront");
        $cart->addToCartItem($cartItem);
        $cart->save(true);

        $json = $cart->toJSON();
        $this->assertSame('{"id":1,"name":"Jamie Woon","cartItem":[{"id":1,"name":"Wayfaring Stranger","cartId":1,"cart":null},{"id":2,"name":"Waterfront","cartId":1,"cart":null}]}', $json);
    }

}