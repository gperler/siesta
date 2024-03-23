<?php

namespace Siesta\Config;

use ReflectionException;
use Siesta\Database\ConnectionData;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Exception\ConnectException;
use Siesta\Exception\InvalidConfigurationException;
use Siesta\Util\ArrayUtil;
use Siesta\Util\File;


class Config
{

    const CONFIG_FILE_NAME = "siesta.config.json";

    const CONFIG_CONNECTION = "connection";

    const CONFIG_GENERATOR = "generator";

    const CONFIG_REVERSE = "reverse";

    const EXCEPTION_NO_CONFIG = "found no config file in %s I searched for %s";

    const EXCEPTION_MISSING_CONNECTION = "found no %s array";

    /**
     * @var Config|null
     */
    private static ?Config $instance = null;

    /**
     * @param string|null $configFileName
     *
     * @return Config
     * @throws InvalidConfigurationException
     * @throws ConnectException
     */
    public static function getInstance(string $configFileName = null): Config
    {
        if (self::$instance === null) {
            self::$instance = new Config($configFileName);
            self::$instance->configureConnections();
        }
        return self::$instance;
    }

    /**
     * @param ConnectionData $connectionData
     * @return array
     */
    public static function buildConfiguration(ConnectionData $connectionData): array
    {
        $reverseConfig = new ReverseConfig();
        $generatorConfig = new MainGeneratorConfig();
        return [
            self::CONFIG_CONNECTION => [
                $connectionData->toArray()
            ],
            self::CONFIG_GENERATOR => $generatorConfig->toArray(),
            self::CONFIG_REVERSE => $reverseConfig->toArray()
        ];
    }

    /**
     *
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * @var array
     */
    protected array $jsonConfig;

    /**
     * @var string
     */
    protected string $configFilePath;

    /**
     * @var MainGeneratorConfig|null
     */
    protected ?MainGeneratorConfig $mainGeneratorConfig;

    /**
     * @var ReverseConfig|null
     */
    protected ?ReverseConfig $reverseConfig;

    /**
     * @param string|null $configFileName
     *
     * @throws InvalidConfigurationException
     * @throws ConnectException
     */
    public function __construct(string $configFileName = null)
    {
        $this->mainGeneratorConfig = null;
        $this->reverseConfig = null;
        $this->findConfig($configFileName);

    }

    /**
     * @return string
     */
    public function getConfigFileName(): string
    {
        return $this->configFilePath;
    }

    /**
     * @param string|null $configFileName
     *
     * @return void
     * @throws InvalidConfigurationException
     */
    private function findConfig(string $configFileName = null): void
    {
        $configFile = null;

        if ($configFileName !== null) {
            $configFile = $this->findConfigFile($configFileName);
        }

        if ($configFile !== null) {
            $this->jsonConfig = $configFile->loadAsJSONArray();
            $this->configFilePath = $configFile->getAbsoluteFileName();
            return;
        }

        $currentWorkDir = new File(getcwd());
        $configFile = $currentWorkDir->findFile(self::CONFIG_FILE_NAME);
        if ($configFile) {
            $this->jsonConfig = $configFile->loadAsJSONArray();
            $this->configFilePath = $configFile->getAbsoluteFileName();
            return;
        }

        throw new InvalidConfigurationException(sprintf(self::EXCEPTION_NO_CONFIG, getcwd(), self::CONFIG_FILE_NAME));
    }

    /**
     * checks if configFileName is absolute or relative
     *
     * @param $configFileName
     *
     * @return File
     */
    private function findConfigFile($configFileName): File
    {
        // try as absolute file
        $configFileAbsolute = new File($configFileName);
        if ($configFileAbsolute->exists()) {
            return $configFileAbsolute;
        }
        // try as relative filename
        $configFileRelative = new File(getcwd() . "/" . trim($configFileName, "/"));
        if ($configFileRelative->exists()) {
            return $configFileRelative;
        }

        // try as filename
        $currentWorkDir = new File(getcwd());
        return $currentWorkDir->findFile($configFileName);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws ConnectException
     */
    protected function configureConnections(): void
    {
        $connectionList = ArrayUtil::getFromArray($this->jsonConfig, self::CONFIG_CONNECTION);
        if ($connectionList === null) {
            throw new InvalidConfigurationException(sprintf(self::EXCEPTION_MISSING_CONNECTION, self::CONFIG_CONNECTION));
        }
        if (!is_array($connectionList)) {
            $connectionList = [$connectionList];
        }

        foreach ($connectionList as $connection) {
            $connectionData = new ConnectionData();
            $connectionData->fromArray($connection);
            ConnectionFactory::addConnection($connectionData);
        }
    }

    /**
     * @return MainGeneratorConfig
     * @throws ReflectionException
     */
    public function getMainGeneratorConfig(): MainGeneratorConfig
    {
        if ($this->mainGeneratorConfig === null) {
            $this->mainGeneratorConfig = new MainGeneratorConfig(ArrayUtil::getFromArray($this->jsonConfig, self::CONFIG_GENERATOR));
        }
        return $this->mainGeneratorConfig;
    }

    /**
     * @return ReverseConfig
     * @throws ReflectionException
     */
    public function getReverseConfig(): ReverseConfig
    {
        if ($this->reverseConfig === null) {
            $this->reverseConfig = new ReverseConfig(ArrayUtil::getFromArray($this->jsonConfig, self::CONFIG_REVERSE));
        }
        return $this->reverseConfig;
    }

}