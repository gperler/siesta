<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Codeception\Util\Debug;
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
    public function generate(Entity $entity, ClassGenerator $classGenerator)
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
    protected function generateStandardMember()
    {
        $this->classGenerator->addProtectedProperty("_existing", "bool");
    }

    /**
     *
     */
    protected function generateAttributeMember()
    {
        foreach ($this->entity->getAttributeList() as $attribute) {
            $this->classGenerator->addProtectedProperty($attribute->getPhpName(), $attribute->getFullyQualifiedTypeName());
        }
    }

    /**
     *
     */
    protected function generateReferenceMember()
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $this->classGenerator->addProtectedProperty($reference->getName(), $foreignEntity->getInstantiationClassName());
        }
    }

    /**
     *
     */
    protected function generateCollectionMember()
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $foreignEntity = $collection->getForeignEntity();
            $this->classGenerator->addProtectedProperty($collection->getName(), $foreignEntity->getInstantiationClassName() . '[]');
        }
    }

    /**
     *
     */
    protected function generateCollectionManyMember()
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $foreignEntity = $collectionMany->getForeignEntity();

            $this->classGenerator->addProtectedProperty($collectionMany->getName(), $foreignEntity->getInstantiationClassName() . '[]');

            $mappingEntity = $collectionMany->getMappingEntity();
            $this->classGenerator->addProtectedProperty($collectionMany->getName() . 'Mapping', $mappingEntity->getInstantiationClassName() . '[]');
        }
    }
}