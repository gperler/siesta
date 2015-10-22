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
        //$this->dropDatabase();

    }

    public function testIndexList()
    {
        $artist = new Artist();

        for($i=0;$i<3;$i++) {
            $artist->setInt($i);
            $artist->setFloat($i);
            $artist->setString("s" . $i);
            $artist->save();
        }

        $resultSet = $this->connection->query("SELECT * FROM " . Artist::DELIMIT_TABLE_NAME);
        $i = 0;
        while($resultSet->hasNext()) {
            $this->assertSame($resultSet->getIntegerValue(Artist::COLUMN_INT), $i);
            $this->assertSame($resultSet->getFloatValue(Artist::COLUMN_FLOAT), (float) $i);
            $this->assertSame($resultSet->getStringValue(ARTIST::COLUMN_STRING), "s" . $i);

            $i++;
        }

    }

}