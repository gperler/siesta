<?php

declare(strict_types = 1);

namespace Siesta\Main;

use Siesta\Database\Connection;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\MetaData\DatabaseMetaData;
use Siesta\Database\MetaData\TableMetaData;
use Siesta\NamingStrategy\NamingStrategy;
use Siesta\NamingStrategy\NamingStrategyRegistry;
use Siesta\Util\File;
use Siesta\XML\XMLEntity;
use Siesta\XML\XMLWrite;

/**
 * @author Gregor Müller
 */
class Reverse
{
    /**
     * @var string
     */
    protected $entityXMLFileSuffix;

    /**
     * @var string
     */
    protected $defaultNamespace;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * Reverse constructor.
     */
    public function __construct()
    {
        $this->entityXMLFileSuffix = ".reverse.xml";
    }

    /**
     * @param string $targetFileName
     */
    public function createSingleXMLFile(string $targetFileName)
    {
        $xmlWrite = $this->getXMLWrite();
        $databaseMetaData = $this->getDatabaseMetaData();

        foreach ($databaseMetaData->getTableList() as $tableMetaData) {
            $this->createAndInitializeXMLEntity($tableMetaData, $xmlWrite);
        }
        $targetFile = new File($targetFileName);
        $targetFile->createDirForFile();
        $xmlWrite->saveToFile($targetFile);
    }

    /**
     * @param string $targetDirectory
     */
    public function createXMLFileForEveryTable(string $targetDirectory)
    {
        $databaseMetaData = $this->getDatabaseMetaData();
        foreach ($databaseMetaData->getTableList() as $tableMetaData) {
            $xmlWrite = $this->getXMLWrite();
            $this->createAndInitializeXMLEntity($tableMetaData, $xmlWrite);
            $targetFile = $this->getTargetFile($targetDirectory, $tableMetaData);
            $xmlWrite->saveToFile($targetFile);
        }
    }

    /**
     * @param string $targetDirectory
     * @param TableMetaData $tableMetaData
     *
     * @return File
     */
    protected function getTargetFile(string $targetDirectory, TableMetaData $tableMetaData) : File
    {
        $filePath = $targetDirectory . "/" . $tableMetaData->getName() . $this->getEntityXMLFileSuffix();
        $targetFile = new File($filePath);
        $targetFile->createDirForFile();
        return $targetFile;
    }

    /**
     * @param TableMetaData $tableMetaData
     * @param XMLWrite $xmlWrite
     *
     * @return XMLEntity
     */
    protected function createAndInitializeXMLEntity(TableMetaData $tableMetaData, XMLWrite $xmlWrite)
    {
        $xmlEntity = new XMLEntity();
        $xmlEntity->setNamespaceName($this->getDefaultNamespace());
        $xmlEntity->setTargetPath($this->getTargetPath());
        $xmlEntity->fromTable($tableMetaData);
        $xmlEntity->toXML($xmlWrite);
        return $xmlEntity;
    }

    /**
     * @return XMLWrite
     */
    protected function getXMLWrite()
    {
        $document = new \DOMDocument("1.0");
        return new XMLWrite($document, null, "entityList");
    }

    /**
     * @return DatabaseMetaData
     */
    public function getDatabaseMetaData() : DatabaseMetaData
    {
        $connection = $this->getConnection();
        return $connection->getDatabaseMetaData($this->getDatabaseName());
    }

    /**
     * @return string
     */
    public function getDefaultNamespace()
    {
        return $this->defaultNamespace;
    }

    /**
     * @param string $defaultNamespace
     */
    public function setDefaultNamespace(string $defaultNamespace = null)
    {
        $this->defaultNamespace = $defaultNamespace;
    }

    /**
     * @return Connection
     */
    public function getConnection() : Connection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }
        return ConnectionFactory::getConnection();
    }

    /**
     * @param string $connectionName
     */
    public function setConnectionName(string $connectionName = null)
    {
        $this->connection = ConnectionFactory::getConnection($connectionName);
    }

    /**
     * @param Connection $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;
    }

    /**
     * @return string
     */
    public function getEntityXMLFileSuffix()
    {
        return $this->entityXMLFileSuffix;
    }

    /**
     * @param string $entityXMLFileSuffix
     */
    public function setEntityXMLFileSuffix(string $entityXMLFileSuffix = null)
    {
        $this->entityXMLFileSuffix = $entityXMLFileSuffix;
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * @param string $targetPath
     */
    public function setTargetPath(string $targetPath)
    {
        $this->targetPath = $targetPath;
    }

    /**
     * @param NamingStrategy $namingStrategy
     */
    public function setClassNamingStrategy(NamingStrategy $namingStrategy)
    {
        NamingStrategyRegistry::setClassNamingStrategy($namingStrategy);
    }

    /**
     * @param NamingStrategy $attributeNamingStrategy
     */
    public function setAttributeNamingStrategy(NamingStrategy $attributeNamingStrategy)
    {
        NamingStrategyRegistry::setAttributeNamingStrategy($attributeNamingStrategy);
    }

}