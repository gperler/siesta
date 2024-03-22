<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\Entity\SavePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class BatchSavePlugin extends BasePlugin
{
    const METHOD_BATCH_SAVE = "batchSave";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return [];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);
        $this->generateBatchSave();
    }

    /**
     *
     */
    protected function generateBatchSave(): void
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_BATCH_SAVE);
        $helper = new GeneratorHelper($method);

        $method->addParameter($this->entity->getInstantiationClassName() . '[]', 'entityList');
        $helper->addConnectionNameParameter();

        // create the batch call
        $method->addCodeLine('$batchCall = "";');
        $method->addForeachStart('$entityList as $entity');
        $method->addCodeLine('$batchCall .= $entity->' . SavePlugin::METHOD_CREATE_SP_CALL_STATEMENT . '();');
        $method->addForeachEnd();

        $helper->addConnectionLookup();
        $method->addCodeLine('$connection->execute($batchCall);');
    }
}