<?php

namespace SiestaTest\End2End\Util;

use Siesta\Database\Connection;
use Siesta\Database\ConnectionFactory;
use Siesta\Main\Siesta;
use Siesta\Model\DataModel;
use Siesta\Util\File;
use Siesta\XML\XMLReader;
use SiestaTest\TestUtil\CodeceptionLogger;

class End2EndTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function resetSchema()
    {
        $connection = ConnectionFactory::getConnection();
        $connection->query("DROP DATABASE IF EXISTS " . $connection->getDatabase());
        $connection->query("CREATE DATABASE " . $connection->getDatabase());
        $connection->useDatabase($connection->getDatabase());
    }

    protected function deleteGenDir($dirName)
    {
        $file = new File($dirName);

        foreach ($file->findFileList("php") as $genFile) {
            $genFile->delete();
        }
    }

    /**
     * @param File $file
     * @param string $targetPath
     * @param bool $silent
     * @throws \ReflectionException
     */
    protected function generateSchema(File $file, string $targetPath, bool $silent = true): void
    {
        $siesta = new Siesta();

        $siesta->setLogger(new CodeceptionLogger($silent));

        $siesta->addFile($file);

        $siesta->migrateDirect($targetPath, true);
    }

    /**
     * @return Connection
     */
    protected function getConnection(): Connection
    {
        return ConnectionFactory::getConnection();
    }

    /**
     * @param $fileName
     *
     * @return DataModel
     */
    public function readModel(string $fileName): DataModel
    {

        $xmlReader = new XMLReader(new File($fileName));
        $xmlEntityList = $xmlReader->getEntityList();

        $dataModel = new DataModel();
        $dataModel->addXMLEntityList($xmlEntityList);
        $dataModel->update();
        return $dataModel;

    }

}