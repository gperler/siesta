<?php

declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\Contract\Generator;
use Siesta\Contract\Plugin;
use Siesta\Model\Entity;
use Siesta\Util\StringUtil;

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

        if (sizeof($interfaceList) === 0) {
            return null;
        }

        return implode(", ", $interfaceList);
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
        $useClassList = array_unique($useClassList);

        return $this->cleanUseClassNameList($entity, $useClassList);
    }

    /**
     * @param Entity $entity
     * @param array $useClassList
     *
     * @return string[]
     */
    protected function cleanUseClassNameList(Entity $entity, array $useClassList) : array
    {
        $result = [];
        foreach ($useClassList as $useClass) {
            $useClass = ltrim($useClass, "\\");

            $namespaceName = StringUtil::getStartBeforeLast($useClass, "\\");
            if ($namespaceName !== $entity->getNamespaceName()) {
                $result[] = $useClass;
            }

        }
        sort($result);
        return $result;
    }
}