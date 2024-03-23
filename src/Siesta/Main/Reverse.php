<?php

declare(strict_types=1);

namespace Siesta\Main;

use DOMDocument;
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
     * @var string|null
     */
    protected ?string $entityXMLFileSuffix;

    /**
     * @var string|null
     */
    protected ?string $defaultNamespace;

    /**
     * @var string|null
     */
    protected ?string $targetPath;

    /**
     * @var Connection|null
     */
    protected ?Connection $connection;

    /**
     * @var string|null
     */
    protected ?string $databaseName;

    /**
     * Reverse constructor.
     */
    public function __construct()
    {
        $this->entityXMLFileSuffix = ".reverse.xml";
        $this->defaultNamespace = null;
        $this->targetPath = null;
        $this->connection = null;
        $this->databaseName = null;
    }

    /**
     * @param string $targetFileName
     */
    public function createSingleXMLFile(string $targetFileName): void
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
    public function createXMLFileForEveryTable(string $targetDirectory): void
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
    protected function getTargetFile(string $targetDirectory, TableMetaData $tableMetaData): File
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
    protected function createAndInitializeXMLEntity(TableMetaData $tableMetaData, XMLWrite $xmlWrite): XMLEntity
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
    protected function getXMLWrite(): XMLWrite
    {
        $document = new DOMDocument("1.0");
        return new XMLWrite($document, null, "entityList");
    }

    /**
     * @return DatabaseMetaData
     */
    public function getDatabaseMetaData(): DatabaseMetaData
    {
        $connection = $this->getConnection();
        return $connection->getDatabaseMetaData($this->getDatabaseName());
    }

    /**
     * @return string|null
     */
    public function getDefaultNamespace(): ?string
    {
        return $this->defaultNamespace;
    }

    /**
     * @param string|null $defaultNamespace
     */
    public function setDefaultNamespace(string $defaultNamespace = null): void
    {
        $this->defaultNamespace = $defaultNamespace;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }
        return ConnectionFactory::getConnection();
    }

    /**
     * @param string|null $connectionName
     */
    public function setConnectionName(string $connectionName = null): void
    {
        $this->connection = ConnectionFactory::getConnection($connectionName);
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @return string
     */
    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     */
    public function setDatabaseName(string $databaseName): void
    {
        $this->databaseName = $databaseName;
    }

    /**
     * @return string
     */
    public function getEntityXMLFileSuffix(): string
    {
        return $this->entityXMLFileSuffix;
    }

    /**
     * @param string|null $entityXMLFileSuffix
     */
    public function setEntityXMLFileSuffix(string $entityXMLFileSuffix = null): void
    {
        $this->entityXMLFileSuffix = $entityXMLFileSuffix;
    }

    /**
     * @return string|null
     */
    public function getTargetPath(): ?string
    {
        return $this->targetPath;
    }

    /**
     * @param string $targetPath
     */
    public function setTargetPath(string $targetPath): void
    {
        $this->targetPath = $targetPath;
    }

    /**
     * @param NamingStrategy $namingStrategy
     */
    public function setClassNamingStrategy(NamingStrategy $namingStrategy): void
    {
        NamingStrategyRegistry::setClassNamingStrategy($namingStrategy);
    }

    /**
     * @param NamingStrategy $attributeNamingStrategy
     */
    public function setAttributeNamingStrategy(NamingStrategy $attributeNamingStrategy): void
    {
        NamingStrategyRegistry::setAttributeNamingStrategy($attributeNamingStrategy);
    }

}