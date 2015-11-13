<?php

namespace siestaphp;

use siestaphp\driver\ConnectionData;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\ConnectException;
use siestaphp\exceptions\InvalidConfiguration;
use siestaphp\generator\GeneratorConfig;
use siestaphp\generator\ReverseGeneratorConfig;
use siestaphp\util\File;
use siestaphp\util\Util;

/**
 * Class Config
 * @package siestaphp
 */
class Config
{

    const CONFIG_FILE_NAME = "siesta.config.json";

    const CONFIG_CONNECTION = "connection";

    const CONFIG_GENERATOR = "generator";

    const CONFIG_REVERSE_GENERATOR = "reverse";

    const CONFIG_CONNECTION_POST_CONNECT = "postConnectStatementList";

    const EXCEPTION_NO_CONFIG = "found no config file in %s I searched for %s";

    const EXCEPTION_MISSING_CONNECTION = "found no %s array";

    /**
     * @var Config
     */
    private static $instance;

    /**
     * @param string $configFileName
     *
     * @return Config
     * @throws InvalidConfiguration
     * @throws ConnectException
     */
    public static function getInstance($configFileName = null)
    {
        if (self::$instance === null) {
            self::$instance = new Config($configFileName);
            self::$instance->configureConnections();
        }
        return self::$instance;
    }

    /**
     *
     */
    public static function reset() {
        self::$instance = null;
    }

    /**
     * @var array
     */
    protected $jsonConfig;

    /**
     * @var string
     */
    protected $configFilePath;

    /**
     * @var GeneratorConfig
     */
    protected $generatorConfig;

    /**
     * @var ReverseGeneratorConfig
     */
    protected $reverseGeneratorConfig;

    /**
     * @param string $configFileName
     *
     * @throws InvalidConfiguration
     * @throws ConnectException
     */
    public function __construct($configFileName = null)
    {
        $this->findConfig($configFileName);
    }

    /**
     * @return string
     */
    public function getConfigFileName()
    {
        return $this->configFilePath;
    }

    /**
     * @param string $configFileName
     *
     * @return void
     * @throws InvalidConfiguration
     */
    private function findConfig($configFileName = null)
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

        throw new InvalidConfiguration(sprintf(self::EXCEPTION_NO_CONFIG, getcwd(), self::CONFIG_FILE_NAME));
    }

    /**
     * checks if configFileName is absolute or relative
     *
     * @param $configFileName
     *
     * @return File
     */
    private function findConfigFile($configFileName)
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
     * @throws InvalidConfiguration
     * @throws ConnectException
     */
    protected function configureConnections()
    {
        $connectionList = Util::getFromArray($this->jsonConfig, self::CONFIG_CONNECTION);
        if ($connectionList === null) {
            throw new InvalidConfiguration(sprintf(self::EXCEPTION_MISSING_CONNECTION, self::CONFIG_CONNECTION));
        }
        if (!is_array($connectionList)) {
            $connectionList = array($connectionList);
        }

        foreach ($connectionList as $connection) {
            $connectionData = new ConnectionData();
            $connectionData->fromArray($connection);
            ConnectionFactory::addConnection($connectionData);
        }
    }

    /**
     * @return GeneratorConfig
     */
    public function getGeneratorConfig()
    {
        if ($this->generatorConfig === null) {
            $this->generatorConfig = new GeneratorConfig(Util::getFromArray($this->jsonConfig, self::CONFIG_GENERATOR));
        }
        return $this->generatorConfig;
    }

    /**
     * @return ReverseGeneratorConfig
     */
    public function getReverseGeneratorConfig()
    {
        if ($this->reverseGeneratorConfig === null) {
            $this->reverseGeneratorConfig = new ReverseGeneratorConfig(Util::getFromArray($this->jsonConfig, self::CONFIG_REVERSE_GENERATOR));
        }
        return $this->reverseGeneratorConfig;
    }

}