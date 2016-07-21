<?php

namespace SiestaTest\End2End\CustomServiceClass\ServiceClass;

class ServiceFactory
{

    /**
     * @return ServiceFactoryChild
     */
    public static function getInstance()
    {
        return new ServiceFactoryChild();
    }

}