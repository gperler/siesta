<?php

namespace SiestaTest\MySQL\Util;

use Codeception\Test\Unit;
use Siesta\Database\Connection;
use Siesta\Database\ConnectionFactory;
use Siesta\Main\Siesta;
use Siesta\Util\File;
use SiestaTest\TestUtil\CodeceptionLogger;

class MySQLTest extends Unit
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

    /**
     * @param $dirName
     */
    protected function deleteGenDir($dirName)
    {
        $file = new File($dirName);
        $file->deleteRecursively();
    }

    /**
     * @param File $file
     * @param string $targetPath
     * @param bool $silent
     */
    protected function generateSchema(File $file, string $targetPath, bool $silent = true)
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

}