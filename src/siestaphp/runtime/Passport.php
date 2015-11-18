<?php

namespace siestaphp\runtime;

use siestaphp\util\Util;

/**
 * Class Passport stores all ORMEntity objects visited to avoid cycles. It will check if a further travelling of
 * entities is allows.
 * @package siestaphp\runtime
 */
class Passport
{

    /**
     * @var array
     */
    private $stamps;

    /**
     *
     */
    public function __construct()
    {
        $this->stamps = [];
    }

    /**
     * @param string $tableName
     * @param ORMEntity $visitor
     *
     * @return bool
     */
    public function furtherTravelAllowed($tableName, ORMEntity $visitor)
    {
        // check if we have an entry for the business object
        $objTravels = Util::getFromArray($this->stamps, $tableName);

        // if object has not been visited
        if (!$objTravels) {
            // create a new entry for the id
            $this->stamps [$tableName] = [];

            // add the id travel
            $this->addTravel($tableName, $visitor);

            // travel can continue
            return true;
        }

        // iterate object from the given table name
        foreach ($objTravels as $travel) {
            if ($visitor->arePrimaryKeyIdentical($travel)) {
                return false;
            }
        }

        // current object has not been visited
        $this->addTravel($tableName, $visitor);

        //
        return true;
    }

    /**
     * @param string $technicalName
     * @param ORMEntity $visitor
     *
     * @return void
     */
    private function addTravel($technicalName, $visitor)
    {
        $this->stamps [$technicalName] [] = $visitor;
    }
}

