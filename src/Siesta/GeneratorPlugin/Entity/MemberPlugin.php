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
        $codeGenerator->addProtectedMember("_existing", "bool");
        $codeGenerator->addProtectedMember("_rawJSON", "array");
        $codeGenerator->addProtectedMember("_rawSQLResult", "array");

        foreach ($entity->getAttributeList() as $attribute) {
            $codeGenerator->addProtectedMember($attribute->getPhpName(), $attribute->getPhpType());
        }

        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $codeGenerator->addProtectedMember($reference->getName(), $foreignEntity->getClassShortName());
        }

        foreach ($entity->getCollectionList() as $collection) {
            $foreignEntity = $collection->getForeignEntity();
            $codeGenerator->addProtectedMember($collection->getName(), $foreignEntity->getInstantiationClassShortName() . '[]');
        }

        foreach ($entity->getCollectionManyList() as $collectionMany) {
            $foreignEntity = $collectionMany->getForeignEntity();

            $codeGenerator->addProtectedMember($collectionMany->getName(), $foreignEntity->getInstantiationClassShortName() . '[]');

            $mappingEntity = $collectionMany->getMappingEntity();
            $codeGenerator->addProtectedMember($collectionMany->getName() . 'Mapping', $mappingEntity->getInstantiationClassShortName() . '[]');
        }
    }

}