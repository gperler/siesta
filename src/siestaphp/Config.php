<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 20.10.15
 * Time: 00:25
 */

namespace siestaphp;

use siestaphp\driver\ConnectionData;
use siestaphp\driver\ConnectionFactory;
use siestaphp\driver\exceptions\DatabaseConfigurationException;
use siestaphp\exceptions\InvalidConfiguration;
use siestaphp\exceptions\NoConfigFileException;
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
     * @throws NoConfigFileException
     * @throws DatabaseConfigurationException
     */
    public static function getInstance($configFileName = null)
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * @var array
     */
    protected $jsonConfig;

    /**
     * @param string $configFileName
     *
     * @throws NoConfigFileException
     * @throws DatabaseConfigurationException
     */
    public function __construct($configFileName = null)
    {
        $this->findConfigFile($configFileName);

        $this->configureConnections();
    }

    /**
     * @param string $configFileName
     *
     * @return void
     * @throws NoConfigFileException
     */
    private function findConfigFile($configFileName = null)
    {
        $configFile = new File($configFileName);
        if ($configFile->exists()) {
            $this->jsonConfig = $configFile->loadAsJSONArray();
            return;
        }
        $currentWorkDir = new File(getcwd());
        $configFile = $currentWorkDir->findFile(self::CONFIG_FILE_NAME);
        if ($configFile) {
            $this->jsonConfig = $configFile->loadAsJSONArray();
            return;
        }
        throw new NoConfigFileException(sprintf(self::EXCEPTION_NO_CONFIG, getcwd(), self::CONFIG_FILE_NAME));
    }

    /**
     * @throws InvalidConfiguration
     */
    private function configureConnections() {
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

}