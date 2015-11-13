<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\Config;
use siestaphp\driver\exceptions\ForeignKeyConstraintFailedException;
use siestaphp\tests\functional\constraint\gen\Address;
use siestaphp\tests\functional\constraint\gen\Customer;

/**
 * Class ReferenceTest
 */
class ConfigTest extends SiestaTester
{

    const ASSET_PATH = "/config";

    const SRC_XML = "/Constraint.test.xml";


    public function testConfig() {
        $config = Config::getInstance();

        Debug::debug($config->getConfigFileName());
    }


}