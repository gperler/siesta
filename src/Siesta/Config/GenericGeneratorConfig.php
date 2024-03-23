<?php

namespace Siesta\Config;

use ReflectionClass;
use ReflectionException;
use Siesta\Model\ValidationLogger;
use Siesta\Util\ArrayUtil;
use Siesta\Util\ClassUtil;
use Siesta\Validator\Validator;

class GenericGeneratorConfig
{
    const GENERATOR_INTERFACE = 'Siesta\Contract\Generator';

    const PLUGIN_INTERFACE = 'Siesta\Contract\Plugin';

    const ERROR_GENERATOR_HAS_NO_NAME = "Found generator without name.";

    const ERROR_GENERATOR_HAS_NO_NAME_CODE = 100;

    const ERROR_GENERATOR_CLASS_DOES_NOT_EXIST = "Generator class '%s' does not exist or is not (auto)loaded";

    const ERROR_GENERATOR_CLASS_DOES_NOT_EXIST_CODE = 101;

    const ERROR_GENERATOR_CLASS_DOES_NOT_IMPLEMENT = "Generator class '%s' does not implement Siesta\\Contract\\Generator";

    const ERROR_GENERATOR_CLASS_DOES_NOT_IMPLEMENT_CODE = 101;

    const ERROR_GENERATOR_PLUGIN_IS_NOT_ARRAY = "pluginList defined for '%s' is not an array";

    const ERROR_GENERATOR_PLUGIN_IS_NOT_ARRAY_CODE = 103;

    const ERROR_GENERATOR_PLUGIN_DOES_NOT_EXIST = "Plugin '%s' defined in '%s' does not exist or is not (auto) loaded";

    const ERROR_GENERATOR_PLUGIN_DOES_NOT_EXIST_CODE = 104;

    const ERROR_GENERATOR_PLUGIN_DOES_NOT_IMPLEMENT = "Plugin '%s' defined in '%s' does not implement Siesta\\Contract\\Plugin";

    const ERROR_GENERATOR_PLUGIN_DOES_NOT_IMPLEMENT_CODE = 105;

    const ERROR_GENERATOR_VALIDATOR_IS_NOT_ARRAY = "validatorList defined for '%s' is not an array";

    const ERROR_GENERATOR_VALIDATOR_IS_NOT_ARRAY_CODE = 106;

    const ERROR_GENERATOR_VALIDATOR_DOES_NOT_EXIST = "Validator '%s' defined in '%s' does not exist or is not (auto) loaded";

    const ERROR_GENERATOR_VALIDATOR_DOES_NOT_EXIST_CODE = 107;

    const ERROR_GENERATOR_VALIDATOR_DOES_NOT_IMPLEMENT = "Validator '%s' defined in '%s' does implement any Validator interface";

    const ERROR_GENERATOR_VALIDATOR_DOES_NOT_IMPLEMENT_CODE = 108;

    const NAME = "name";

    const CLASSNAME = "className";

    const PLUGIN_LIST = "pluginList";

    const VALIDATOR_LIST = "validatorList";

    /**
     * @var string|null
     */
    protected ?string $name;

    /**
     * @var string|null
     */
    protected ?string $className;

    /**
     * @var string[]
     */
    protected ?array $pluginList;

    /**
     * @var string[]
     */
    protected ?array $validatorList;

    /**
     * GeneratorConfig constructor.
     *
     * @param array $valueList
     */
    public function __construct(array $valueList)
    {
        $this->name = ArrayUtil::getFromArray($valueList, self::NAME);
        $this->className = ArrayUtil::getFromArray($valueList, self::CLASSNAME);
        $this->pluginList = ArrayUtil::getFromArray($valueList, self::PLUGIN_LIST);
        $this->validatorList = ArrayUtil::getFromArray($valueList, self::VALIDATOR_LIST);
    }

    /**
     * @param ValidationLogger $logger
     * @throws ReflectionException
     */
    public function validate(ValidationLogger $logger): void
    {
        if (empty($this->name)) {
            $logger->error(self::ERROR_GENERATOR_HAS_NO_NAME, self::ERROR_GENERATOR_HAS_NO_NAME_CODE);
        }

        $this->validateGenerator($logger);

        if (!is_array($this->pluginList)) {
            $error = sprintf(self::ERROR_GENERATOR_PLUGIN_IS_NOT_ARRAY, $this->name);
            $logger->error($error, self::ERROR_GENERATOR_PLUGIN_IS_NOT_ARRAY_CODE);
        } else {
            $this->validatePluginList($logger);
        }

        if (!is_array($this->validatorList)) {
            $error = sprintf(self::ERROR_GENERATOR_VALIDATOR_IS_NOT_ARRAY, $this->name);
            $logger->error($error, self::ERROR_GENERATOR_VALIDATOR_IS_NOT_ARRAY_CODE);
        } else {
            $this->validateValidatorList($logger);
        }

    }

    /**
     * @param ValidationLogger $logger
     */
    protected function validateGenerator(ValidationLogger $logger): void
    {
        if (empty($this->className) || !class_exists($this->className)) {
            $error = sprintf(self::ERROR_GENERATOR_CLASS_DOES_NOT_EXIST, $this->className);
            $logger->error($error, self::ERROR_GENERATOR_CLASS_DOES_NOT_EXIST_CODE);
            return;
        }
        $reflect = new ReflectionClass($this->className);

        if (!$reflect->implementsInterface(self::GENERATOR_INTERFACE)) {
            $error = sprintf(self::ERROR_GENERATOR_CLASS_DOES_NOT_IMPLEMENT, $this->className);
            $logger->error($error, self::ERROR_GENERATOR_CLASS_DOES_NOT_IMPLEMENT_CODE);
        }
    }

    /**
     * @param ValidationLogger $logger
     * @throws ReflectionException
     */
    protected function validatePluginList(ValidationLogger $logger): void
    {
        foreach ($this->pluginList as $plugin) {
            $this->validatePlugin($logger, $plugin);
        }
    }

    /**
     * @param ValidationLogger $logger
     * @param string $plugin
     * @throws ReflectionException
     */
    protected function validatePlugin(ValidationLogger $logger, string $plugin): void
    {

        if (!ClassUtil::exists($plugin)) {
            $error = sprintf(self::ERROR_GENERATOR_PLUGIN_DOES_NOT_EXIST, $plugin, $this->getName());
            $logger->error($error, self::ERROR_GENERATOR_PLUGIN_DOES_NOT_EXIST_CODE);
            return;
        }

        if (!ClassUtil::implementsInterface($plugin, self::PLUGIN_INTERFACE)) {
            $error = sprintf(self::ERROR_GENERATOR_PLUGIN_DOES_NOT_IMPLEMENT, $plugin, $this->getName());
            $logger->error($error, self::ERROR_GENERATOR_PLUGIN_DOES_NOT_IMPLEMENT_CODE);
        }
    }

    /**
     * @param ValidationLogger $logger
     * @throws ReflectionException
     */
    protected function validateValidatorList(ValidationLogger $logger): void
    {
        foreach ($this->validatorList as $validator) {
            $this->validateValidator($logger, $validator);
        }
    }

    /**
     * @param ValidationLogger $logger
     * @param $validator
     * @throws ReflectionException
     */
    public function validateValidator(ValidationLogger $logger, $validator): void
    {
        if (!ClassUtil::exists($validator)) {
            $error = sprintf(self::ERROR_GENERATOR_VALIDATOR_DOES_NOT_EXIST, $validator, $this->getName());
            $logger->error($error, self::ERROR_GENERATOR_VALIDATOR_DOES_NOT_EXIST_CODE);
            return;
        }

        $reflect = new ReflectionClass($validator);

        if ($reflect->implementsInterface(Validator::DATA_MODEL_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::ENTITY_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::ATTRIBUTE_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::REFERENCE_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::INDEX_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::COLLECTION_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::COLLECTION_MANY_VALIDATOR_INTERFACE)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::DYNAMIC_COLLECTION_VALIDATOR)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::STORED_PROCEDURE_VALIDATOR)) {
            return;
        }

        if ($reflect->implementsInterface(Validator::VALUE_OBJECT_VALIDATOR)) {
            return;
        }

        $error = sprintf(self::ERROR_GENERATOR_VALIDATOR_DOES_NOT_IMPLEMENT, $validator, $this->getName());
        $logger->error($error, self::ERROR_GENERATOR_VALIDATOR_DOES_NOT_IMPLEMENT_CODE);

    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @return string[]|null
     */
    public function getPluginList(): ?array
    {
        return $this->pluginList;
    }

    /**
     * @return string[]|null
     */
    public function getValidatorList(): ?array
    {
        return $this->validatorList;
    }

}