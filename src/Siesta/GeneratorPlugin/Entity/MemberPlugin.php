<?php

declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MemberPlugin extends BasePlugin
{

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);
        $this->generateStandardMember();
        $this->generateAttributeMember();
        $this->generateReferenceMember();
        $this->generateCollectionMember();
        $this->generateCollectionManyMember();
    }

    /**
     *
     */
    protected function generateStandardMember(): void
    {
        $this->classGenerator->addProtectedProperty("_existing", "bool", 'null');
    }

    /**
     *
     */
    protected function generateAttributeMember(): void
    {
        foreach ($this->entity->getAttributeList() as $attribute) {
            $this->classGenerator->addProtectedProperty($attribute->getPhpName(), $attribute->getFullyQualifiedTypeName(), 'null');
        }
    }

    /**
     *
     */
    protected function generateReferenceMember(): void
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $this->classGenerator->addProtectedProperty($reference->getName(), $foreignEntity->getInstantiationClassName(), 'null');
        }
    }

    /**
     *
     */
    protected function generateCollectionMember(): void
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $foreignEntity = $collection->getForeignEntity();
            $this->classGenerator->addProtectedProperty($collection->getName(), $foreignEntity->getInstantiationClassName() . '[]', 'null');
        }
    }

    /**
     *
     */
    protected function generateCollectionManyMember(): void
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $foreignEntity = $collectionMany->getForeignEntity();

            $this->classGenerator->addProtectedProperty($collectionMany->getName(), $foreignEntity->getInstantiationClassName() . '[]', 'null');

            $mappingEntity = $collectionMany->getMappingEntity();
            $this->classGenerator->addProtectedProperty($collectionMany->getName() . 'Mapping', $mappingEntity->getInstantiationClassName() . '[]', 'null');
        }
    }
}