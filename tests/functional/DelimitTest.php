<?php

namespace siestaphp\tests\functional;

use Codeception\Util\Debug;
use siestaphp\tests\functional\delimit\gen\Artist;

/**
 * Class MultiPKTest
 */
class DelimitTest extends SiestaTester
{

    const ASSET_PATH = "/delimit";

    const SRC_XML = "/Delimit.test.xml";

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

    public function testIndexList()
    {
        $artist = new Artist();

        $artistHistory = array(
          array("name" => "MC Hammer", "city"=>"London"),
            array("name" => "Hammer", "city" =>"Berlin"),
            array("name" => "Dr Hammer", "city"=>"NY")
        );


        for($i=0;$i<3;$i++) {
            $artist->setName($artistHistory[$i]["name"]);
            $artist->setCity($artistHistory[$i]["city"]);
            $artist->save();
        }

        $resultSet = $this->connection->query("SELECT * FROM " . Artist::DELIMIT_TABLE_NAME);
        $i = 0;
        while($resultSet->hasNext()) {
            $this->assertSame($artistHistory[$i]["name"], $resultSet->getStringValue(Artist::COLUMN_NAME), $i);
            $this->assertSame($artistHistory[$i]["city"], $resultSet->getStringValue(Artist::COLUMN_CITY), (float) $i);
            $i++;
        }

    }

}