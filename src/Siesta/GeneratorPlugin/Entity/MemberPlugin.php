<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class MemberPlugin extends BasePlugin
{

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useList = [];
        foreach ($entity->getAttributeList() as $attribute) {
            if ($attribute->getPhpType() === "SiestaDateTime") {
                $useList[] = 'Siesta\Util\SiestaDateTime';
            }
            if ($attribute->getClassName() !== null) {
                $useList[] = $attribute->getClassName();
            }
        }

        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $useList[] = $foreignEntity->getClassName();
        }

        return $useList;
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

        $this->codeGenerator->addProtectedMember("_existing", "bool");
        $this->codeGenerator->addProtectedMember("_rawJSON", "array");
        $this->codeGenerator->addProtectedMember("_rawSQLResult", "array");

    }

    /**
     *
     */
    protected function generateAttributeMember()
    {
        foreach ($this->entity->getAttributeList() as $attribute) {
            $this->codeGenerator->addProtectedMember($attribute->getPhpName(), $attribute->getPhpType());
        }
    }

    /**
     *
     */
    protected function generateReferenceMember()
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $this->codeGenerator->addProtectedMember($reference->getName(), $foreignEntity->getClassShortName());
        }
    }

    /**
     *
     */
    protected function generateCollectionMember()
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $foreignEntity = $collection->getForeignEntity();
            $this->codeGenerator->addProtectedMember($collection->getName(), $foreignEntity->getInstantiationClassShortName() . '[]');
        }
    }

    /**
     *
     */
    protected function generateCollectionManyMember()
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $foreignEntity = $collectionMany->getForeignEntity();

            $this->codeGenerator->addProtectedMember($collectionMany->getName(), $foreignEntity->getInstantiationClassShortName() . '[]');

            $mappingEntity = $collectionMany->getMappingEntity();
            $this->codeGenerator->addProtectedMember($collectionMany->getName() . 'Mapping', $mappingEntity->getInstantiationClassShortName() . '[]');
        }
    }
}