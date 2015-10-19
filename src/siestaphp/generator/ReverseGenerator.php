<?php

namespace siestaphp\generator;

use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ConnectionFactory;
use siestaphp\xmlbuilder\XMLEntityBuilder;

/**
 * Class ReverseGenerator
 * @package siestaphp\generator
 */
class ReverseGenerator
{

    /**
     * @param string $databaseName
     * @param string $targetPath
     * @param string $targetNamespace
     * @param string $singleFile
     *
     * @return void
     */
    public function generateXML($databaseName, $targetPath, $targetNamespace, $singleFile)
    {
        $connection = ConnectionFactory::getConnection();

        $entitySourceList = $connection->getEntitySourceList($databaseName, $targetNamespace, $targetPath);
        foreach ($entitySourceList as $entitySource) {
            $this->generateXMLFiles($entitySource, $targetPath);
        }
    }

    /**
     * @param EntitySource $entitySource
     * @param $targetPath
     *
     * @return void
     */
    private function generateXMLFiles(EntitySource $entitySource, $targetPath)
    {
        $entityBuilder = new XMLEntityBuilder($entitySource);

        $domDocument = $entityBuilder->getDOMDocument();

        $domDocument->formatOutput = true;

        $domDocument->save($targetPath . "/" . $entitySource->getClassName() . ".entity.xml");
    }

}