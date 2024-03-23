<?php

namespace SiestaTest\End2End\Util;

use Siesta\Contract\ArraySerializable;
use Siesta\Util\ArrayUtil;

class AttributeSerialize implements ArraySerializable
{

    protected $y;

    protected $x;

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    public function fromArray(array $data): void
    {
        $this->x = ArrayUtil::getFromArray($data, "x");
        $this->y = ArrayUtil::getFromArray($data, "y");

    }

    public function toArray() : array
    {
        return [
            "x" => $this->x,
            "y" => $this->y
        ];
    }

}