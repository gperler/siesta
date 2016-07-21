<?php
declare(strict_types = 1);

namespace Siesta\Generator;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\Contract\Generator;
use Siesta\Contract\Plugin;
use Siesta\Model\Entity;
use Siesta\Util\File;
use Siesta\Util\StringUtil;

/**
 * @author Gregor Müller
 */
class ServiceGenerator implements Generator
{
    /**
     * @var Plugin[]
     */
    protected $pluginList;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var CodeGenerator
     */
    protected $codeGenerator;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * EntityGenerator constructor.
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

    public function generate(Entity $entity, string $baseDir)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->entity = $entity;
        $this->basePath = $baseDir;

        $this->generateServiceEntity();
        $this->saveServiceEntity();
    }

    /**
     *
     */
    protected function saveServiceEntity()
    {
        $targetFile = $this->getTargetFile();

        $this->codeGenerator->writeTo($targetFile);
    }

    /**
     *
     */
    protected function generateServiceEntity()
    {
        $this->codeGenerator->addNamespace($this->entity->getNamespaceName());

        foreach ($this->getUseClassNameList() as $useClass) {
            $this->codeGenerator->addUse($useClass);
        }

        $this->codeGenerator->newLine();

        $this->codeGenerator->addClassStart($this->entity->getServiceClassShortName());

        $this->codeGenerator->newLine();

        foreach ($this->pluginList as $plugin) {
            $plugin->generate($this->entity, $this->codeGenerator);
        }

        $this->codeGenerator->addClassEnd();
    }

    /**
     * @return File
     */
    protected function getTargetFile() : File
    {
        $basePath = rtrim($this->basePath, DIRECTORY_SEPARATOR);

        $directoryPath = $basePath . DIRECTORY_SEPARATOR . $this->entity->getTargetPath();

        $directory = new File($directoryPath);
        $directory->createDir();

        $targetFileName = $directoryPath . DIRECTORY_SEPARATOR . $this->entity->getServiceClassShortName() . ".php";
        return new File($targetFileName);
    }

    /**
     * @return array
     */
    public function getUseClassNameList()
    {
        $useClassList = [];

        foreach ($this->pluginList as $plugin) {
            $useClassList = array_merge($useClassList, $plugin->getUseClassNameList($this->entity));
        }
        $useClassList = array_unique($useClassList);
        return $this->cleanUseClassNameList($useClassList);

    }

    /**
     * @param array $useClassList
     *
     * @return array
     */
    protected function cleanUseClassNameList(array $useClassList) : array
    {
        $result = [];
        foreach ($useClassList as $useClass) {
            $useClass = ltrim($useClass, "\\");

            $namespaceName = StringUtil::getEndAfterLast($useClass, "\\");
            if ($namespaceName !== $this->entity->getNamespaceName()) {
                $result[] = $useClass;
            }

        }
        sort($result);
        return $result;
    }

}