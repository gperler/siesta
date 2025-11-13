<?php

declare(strict_types=1);

namespace SiestaTest\Functional\ValueObject;


use Codeception\Test\Unit;
use Siesta\Main\Siesta;
use Siesta\Util\File;
use SiestaTest\Functional\ValueObject\Generated\ValueObject;
use SiestaTest\TestUtil\CodeceptionLogger;

class ValueObjectTest extends Unit
{


    public function setUp(): void
    {
        $siesta = new Siesta();

        $siesta->setLogger(new CodeceptionLogger(false));

        $siesta->addFile(new File(__DIR__ . "/Schema/value.object.test.xml"));

        $siesta->migrateDirect(__DIR__, true);
    }


    public function testValueObject()
    {
        $vo = new ValueObject();
        $vo->setPriceCurrency("EUR");
        $vo->setPriceAmount(120);

        $vo->setPrice(null);
        $this->assertNull($vo->getPriceCurrency());
        $this->assertNull($vo->getPriceAmount());


        $price = new Money();
        $price->setPriceAmount(1977);
        $price->setPriceCurrency("EUR");

        $vo->setPrice($price);

        $this->assertSame("EUR", $vo->getPriceCurrency());
        $this->assertSame(1977, $vo->getPriceAmount());

        $price2 = $vo->getPrice();
        $this->assertSame("EUR", $price2->getPriceCurrency());
        $this->assertSame(1977, $price2->getPriceAmount());
    }

}