<?php

declare(strict_types = 1);

namespace SiestaTest\Functional\ValueObject;

class Money
{

    protected $priceAmount;

    protected $priceCurrency;

    /**
     * @return mixed
     */
    public function getPriceAmount()
    {
        return $this->priceAmount;
    }

    /**
     * @param mixed $priceAmount
     */
    public function setPriceAmount($priceAmount)
    {
        $this->priceAmount = $priceAmount;
    }

    /**
     * @return mixed
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * @param mixed $priceCurrency
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

}