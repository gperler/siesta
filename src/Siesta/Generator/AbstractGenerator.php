<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\Contract\Generator;
use Siesta\Contract\Plugin;
use Siesta\Model\Entity;

abstract class AbstractGenerator implements Generator
{

    /**
     * @var Plugin[]
     */
    protected $pluginList;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * AbstractGenerator constructor.
     */
    public function __construct()
    {
        $this->pluginList = [];
    }

    /**
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin)
    {
        $this->pluginList[] = $plugin;
    }

    /**
     * @return null|string
     */
    public function getImplementedInterfaceList()
    {
        $interfaceList = [];
        foreach ($this->pluginList as $plugin) {
            $interfaceList = array_merge($interfaceList, $plugin->getInterfaceList());
        }
        $interfaceList = array_unique($interfaceList);
        sort($interfaceList);

        return $interfaceList;
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity)
    {
        $useClassList = [];

        foreach ($this->pluginList as $plugin) {
            $useClassList = array_merge($useClassList, $plugin->getUseClassNameList($entity));
        }
        return $useClassList;
    }

    /**
     * @param Entity $entity
     * @param string $className
     *
     * @return string
     */
    protected function getTargetFile(Entity $entity, string $className) : string
    {
        $basePath = rtrim($this->basePath, DIRECTORY_SEPARATOR);
        return $basePath . DIRECTORY_SEPARATOR . $entity->getTargetPath() . DIRECTORY_SEPARATOR . $className . ".php";
    }

}