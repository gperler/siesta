<?php

namespace SiestaTest\End2End\Construct\Entity;

class EntityFactory
{

    public static function newFactoryEntity() {
        $factory = new Factory();
        $factory->setString1("FactoryGeneratedEntity");
        return $factory;
    }
    
}