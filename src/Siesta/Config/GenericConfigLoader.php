<?php

namespace Siesta\Config;

use ReflectionException;
use Siesta\Model\ValidationLogger;
use Siesta\Util\ArrayUtil;
use Siesta\Util\File;


class GenericConfigLoader
{

    const ERROR_CONFIG_DOES_NOT_EXIST = "Generator Config file '%s' does not exist.";

    const ERROR_CONFIG_DOES_NOT_EXIST_CODE = 1;

    const ERROR_CONFIG_FILE_IS_EMPTY = "Generator Config file '%s' is invalid or empty";

    const ERROR_CONFIG_FILE_IS_EMPTY_CODE = 2;

    const ERROR_CONFIG_FILE_MISSES_GEN = "Generator Config file '%s' has no 'generatorList' array";

    const ERROR_CONFIG_FILE_MISSES_GEN_CODE = 3;

    const ERROR_NO_GENERIC_GENERATORS_DEFINED = "Generator Config file '%s' has no generators defined.";

    const ERROR_NO_GENERIC_GENERATORS_DEFINED_CODE = 4;

    const GENERATOR_LIST = "generatorList";

    /**
     * @var null|File
     */
    protected ?File $configFile;

    /**
     *
     */
    public function __construct()
    {
        $this->configFile = null;
    }

    public function setConfigFileName(string $filename = null): void
    {
        if ($filename === null) {
            return;
        }
        $this->configFile = new File($filename);
    }

    /**
     * @param File|null $file
     */
    public function setConfigFile(File $file = null): void
    {
        $this->configFile = $file;
    }

    /**
     * @return File
     */
    protected function getConfigFile(): File
    {
        if ($this->configFile === null) {
            $this->configFile = new File(__DIR__ . "/siesta.generator.config.json");
        }
        return $this->configFile;
    }

    /**
     * @param ValidationLogger $logger
     *
     * @return GenericGeneratorConfig[]
     * @throws ReflectionException
     */
    public function loadAndValidate(ValidationLogger $logger): array
    {
        $genericGeneratorConfigList = $this->getGenericGeneratorConfigList($logger, $this->getConfigFile());

        if ($genericGeneratorConfigList === null) {
            return [];
        }

        $configList = $this->initializeGenericGeneratorConfig($logger, $genericGeneratorConfigList);

        if (sizeof($configList) === 0) {
            $error = sprintf(self::ERROR_NO_GENERIC_GENERATORS_DEFINED, $this->configFile->getAbsoluteFileName());
            $logger->error($error, self::ERROR_NO_GENERIC_GENERATORS_DEFINED_CODE);
        }

        return $configList;

    }

    /**
     * @param ValidationLogger $logger
     * @param File $file
     *
     * @return null|string[]
     */
    protected function getGenericGeneratorConfigList(ValidationLogger $logger, File $file): ?array
    {
        if (!$file->exists()) {
            $error = sprintf(self::ERROR_CONFIG_DOES_NOT_EXIST, $file->getAbsoluteFileName());
            $logger->error($error, self::ERROR_CONFIG_DOES_NOT_EXIST_CODE);
            return null;
        }

        $configValues = $file->loadAsJSONArray();

        if ($configValues === null) {
            $error = sprintf(self::ERROR_CONFIG_FILE_IS_EMPTY, $file->getAbsoluteFileName());
            $logger->error($error, self::ERROR_CONFIG_FILE_IS_EMPTY_CODE);
            return null;
        }

        $genericGeneratorConfigList = ArrayUtil::getFromArray($configValues, self::GENERATOR_LIST);

        if ($genericGeneratorConfigList === null || !is_array($genericGeneratorConfigList)) {
            $error = sprintf(self::ERROR_CONFIG_FILE_MISSES_GEN, $file->getAbsoluteFileName());
            $logger->error($error, self::ERROR_CONFIG_FILE_MISSES_GEN_CODE);
        }

        return $genericGeneratorConfigList;
    }

    /**
     * @param ValidationLogger $logger
     * @param array $genericGeneratorConfigList
     *
     * @return GenericGeneratorConfig[]
     * @throws ReflectionException
     */
    protected function initializeGenericGeneratorConfig(ValidationLogger $logger, array $genericGeneratorConfigList): array
    {
        $genericGeneratorConfig = [];
        foreach ($genericGeneratorConfigList as $genericGeneratorConfigValues) {
            $genericConfig = new GenericGeneratorConfig($genericGeneratorConfigValues);
            $genericConfig->validate($logger);
            $genericGeneratorConfig[] = $genericConfig;
        }

        return $genericGeneratorConfig;
    }

}