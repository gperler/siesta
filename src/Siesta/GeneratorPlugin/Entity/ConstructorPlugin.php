<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ConstructorPlugin extends BasePlugin
{
    /**
     * @param Entity $entity
     *
     * @return array
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getDependantPluginList() : array
    {
        return [
            'Siesta\EntityPlugin\Entity\MemberPlugin'
        ];
    }

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);

        $method = $this->codeGenerator->newPublicConstructor();
        $method->addLine('$this->_existing = false;');

        // check for attribute default values
        foreach ($entity->getAttributeList() as $attribute) {
            if ($attribute->getDefaultValue() === null) {
                continue;
            }
            $method->addLine('$this->' . $attribute->getPhpName() . ' = ' . $attribute->getDefaultValue() . ';');
        }

        foreach ($entity->getCollectionManyList() as $collectionMany) {
            $method->addLine('$this->' . $collectionMany->getName() . 'Mapping = [];');
        }

        $method->end();
    }

}