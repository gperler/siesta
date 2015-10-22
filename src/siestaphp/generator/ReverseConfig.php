<?php

namespace siestaphp\generator;

use siestaphp\util\Util;

/**
 * Class ReverseGeneratorConfig
 * @package siestaphp\generator
 */
class ReverseGeneratorConfig
{

    const OPTION_ENTITY_FILE_SUFFX = "entityFileSuffix";

    const OPTION_CONNECTION_NAME = "connectionName";

    const OPTION_TARGET_PATH = "targetPath";

    const OPTION_TARGET_NAMESPACE = "targetNamespace";

    const OPTION_SINGLE_FILE = "singleFile";

    const DEFAULT_REVERSE_TARGET = "/reverse";

    public static $OPTION_LIST = array(self::OPTION_CONNECTION_NAME, self::OPTION_ENTITY_FILE_SUFFX, self::OPTION_SINGLE_FILE, self::OPTION_TARGET_NAMESPACE, self::OPTION_TARGET_PATH

    );

    /**
     * @var string
     */
    protected $connectionName;

    /**
     * @var string
     */
    protected $entityFileSuffix;

    /**
     * @var bool
     */
    protected $singleFile;

    /**
     * @var string
     */
    protected $targetPath;

    /**
     * @var string
     */
    protected $targetNamespace;

    /**
     * @param array $values
     */
    public function __construct($values = null)
    {
        $this->setConnectionName(Util::getFromArray($values, self::OPTION_CONNECTION_NAME));
        $this->setEntityFileSuffix(Util::getFromArray($values, self::OPTION_ENTITY_FILE_SUFFX));
        $this->setSingleFile(Util::getFromArray($values, self::OPTION_SINGLE_FILE));
        $this->setTargetPath(Util::getFromArray($values, self::OPTION_TARGET_PATH));
        $this->setTargetNamespace(Util::getFromArray($values, self::OPTION_TARGET_NAMESPACE));
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setValue($key, $value)
    {
        switch ($key) {
            case self::OPTION_CONNECTION_NAME:
                $this->setConnectionName($value);
                break;

            case self::OPTION_ENTITY_FILE_SUFFX:
                $this->setEntityFileSuffix($value);
                break;

            case self::OPTION_SINGLE_FILE:
                $this->setSingleFile($value);
                break;

            case self::OPTION_TARGET_NAMESPACE:
                $this->setTargetNamespace($value);
                break;

            case self::OPTION_TARGET_PATH:
                $this->setTargetPath($value);
                break;
        }
    }

    /**
     * @return string
     */
    public function getEntityFileSuffix()
    {
        return $this->entityFileSuffix;
    }

    /**
     * @param string $entityFileSuffix
     */
    public function setEntityFileSuffix($entityFileSuffix)
    {
        $this->entityFileSuffix = ($entityFileSuffix) ? $entityFileSuffix : GeneratorConfig::DEFAULT_ENTITY_SUFFIX;
    }

    /**
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connectionName;
    }

    /**
     * @param string $connectionName
     */
    public function setConnectionName($connectionName)
    {
        $this->connectionName = $connectionName;
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
    public function setTargetPath($targetPath)
    {
        $this->targetPath = ($targetPath) ? $targetPath : getcwd() . self::DEFAULT_REVERSE_TARGET;
    }

    /**
     * @return boolean
     */
    public function isSingleFile()
    {
        return $this->singleFile;
    }

    /**
     * @param boolean $singleFile
     */
    public function setSingleFile($singleFile)
    {
        $this->singleFile = $singleFile;
    }

    /**
     * @return string
     */
    public function getTargetNamespace()
    {
        return $this->targetNamespace;
    }

    /**
     * @param string $targetNamespace
     */
    public function setTargetNamespace($targetNamespace)
    {
        $this->targetNamespace = $targetNamespace;
    }

}