<?php

namespace SiestaTest\Functional\Migration;

use Codeception\Util\Debug;
use Siesta\Migration\DatabaseMigrator;
use Siesta\NamingStrategy\ToCamelCaseStrategy;
use Siesta\NamingStrategy\ColumnNamingIdenticalStrategy;
use Siesta\NamingStrategy\ToUnderScoreStrategy;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\Util\File;
use Siesta\XML\XMLEntity;
use SiestaTest\TestDatabase\TestConnection;
use SiestaTest\TestUtil\DataModelHelper;

/**
 * @author Gregor Müller
 */
class NamingStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabase()
    {
        NamingStrategyRegistry::setAttributeNamingStrategy(new ToCamelCaseStrategy());

        $testConnection = new TestConnection();
        $testConnection->setFixtureFile(new File(__DIR__ . "/schema/namingstrategy.attribute.test.schema.json"));

        $metaData = $testConnection->getDatabaseMetaData();
        $table = $metaData->getTableByName("namingStrategy");

        $this->assertNotNull($table);

        $xmlEntity = new XMLEntity();
        $xmlEntity->fromTable($table);

        $camelCaseList = [
            "testNaming",
            "testNamingMore"
        ];

        $attributeList = $xmlEntity->getXMLAttributeList();

        $this->assertSame(2, sizeof($attributeList));

        foreach($attributeList as $attribute) {
            $this->assertTrue(in_array($attribute->getPhpName(), $camelCaseList));
        }
        NamingStrategyRegistry::setAttributeNamingStrategy(new ToCamelCaseStrategy());
    }

}


