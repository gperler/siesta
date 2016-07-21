<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

/**
 * @author Gregor Müller
 */
class JSONConverterPlugin extends BasePlugin
{
    const METHOD_FROM_JSON = "fromJSON";

    const METHOD_TO_JSON = "toJSON";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [
            'Siesta\Contract\CycleDetector'
        ];
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return [
            'Siesta\GeneratorPlugin\Entity\ArrayConverterPlugin'
        ];
    }

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);
        $this->generateFromJson();
        $this->generateToJson();
    }

    /**
     *
     */
    protected function generateFromJson()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_FROM_JSON);
        $method->addParameter(PHPType::STRING, 'jsonString');

        $method->addLine('$this->' . ArrayConverterPlugin::METHOD_FROM_ARRAY . '(json_decode($jsonString, true));');

        $method->end();
    }

    /**
     *
     */
    protected function generateToJson()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_TO_JSON);
        $method->addParameter('CycleDetector','cycleDetector','null');
        $method->setReturnType(PHPType::STRING);

        $method->addLine('return json_encode($this->' . ArrayConverterPlugin::METHOD_TO_ARRAY . '($cycleDetector));');

        $method->end();

    }

}