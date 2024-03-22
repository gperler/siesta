<?php
declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class ConstructorPlugin extends BasePlugin
{

    /**
     * @return array
     */
    public function getDependantPluginList(): array
    {
        return [
            MemberPlugin::class,
        ];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        $method = $this->classGenerator->addConstructor();
        $method->addCodeLine('$this->_existing = false;');

        // check for attribute default values
        foreach ($entity->getAttributeList() as $attribute) {
            if ($attribute->getDefaultValue() === null) {
                continue;
            }
            $method->addCodeLine('$this->' . $attribute->getPhpName() . ' = ' . $attribute->getDefaultValue() . ';');
        }

        foreach ($entity->getCollectionManyList() as $collectionMany) {
            $method->addCodeLine('$this->' . $collectionMany->getName() . 'Mapping = [];');
        }
    }

}