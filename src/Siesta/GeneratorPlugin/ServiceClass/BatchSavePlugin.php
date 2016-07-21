<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
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
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);
        $this->generateBatchSave();
    }

    /**
     *
     */
    protected function generateBatchSave()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_BATCH_SAVE);
        $method->addParameter($this->entity->getInstantiationClassShortName() . '[]', 'entityList');
        $method->addConnectionNameParameter();

        // create the batch call
        $method->addLine('$batchCall = "";');
        $method->addForeachStart('$entityList as $entity');
        $method->addLine('$batchCall .= $entity->' . SavePlugin::METHOD_CREATE_SP_CALL_STATEMENT . '();');
        $method->addForeachEnd();

        $method->addConnectionLookup();
        $method->addLine('$connection->execute($batchCall);');

        $method->end();
    }
}