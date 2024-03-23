<?php
declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Nitria\Method;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\ServiceClass\ForeignCollectionManyPlugin;
use Siesta\GeneratorPlugin\ServiceClass\NewInstancePlugin;
use Siesta\Model\Attribute;
use Siesta\Model\CollectionMany;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

class CollectionManyAccess extends BasePlugin
{
    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getGetterName(CollectionMany $collectionMany): string
    {
        return 'get' . $collectionMany->getMethodName();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getAddToCollectionName(CollectionMany $collectionMany): string
    {
        return 'addTo' . $collectionMany->getMethodName();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getDeleteFromCollectionName(CollectionMany $collectionMany): string
    {
        return 'deleteFrom' . $collectionMany->getMethodName();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getDeleteFromAssigned(CollectionMany $collectionMany): string
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        return 'deleteAssigned' . $foreignEntity->getClassShortName();
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity): array
    {
        $useList = [];
        foreach ($entity->getCollectionManyList() as $collectionMany) {
            $mappingEntity = $collectionMany->getMappingEntity();
            $useList[] = $mappingEntity->getInstantiationClassName();

            $foreignEntity = $collectionMany->getForeignEntity();
            $useList[] = $foreignEntity->getInstantiationClassName();

            $serviceClass = $foreignEntity->getServiceClass();
            if ($serviceClass === null) {
                continue;
            }

            if ($serviceClass->getClassName() !== null) {
                $useList[] = $serviceClass->getClassName();
            }

            if ($serviceClass->getConstructFactoryClassName() !== null) {
                $useList[] = $serviceClass->getConstructFactoryClassName();
            }

        }

        foreach ($entity->getReferenceList() as $reference) {
            $foreignEntity = $reference->getForeignEntity();
            $serviceFactory = $foreignEntity->getServiceFactoryClass();
            if ($serviceFactory !== null) {
                $useList[] = $serviceFactory;
            }
        }
        return $useList;
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $this->generateCollectionManyGetter($collectionMany);
            $this->generateAddToCollection($collectionMany);
            $this->generateDeleteFromCollection($collectionMany);
            $this->generateDeleteAssigned($collectionMany);
        }
    }

    /**
     * @param CollectionMany $collectionMany
     */
    protected function generateCollectionManyGetter(CollectionMany $collectionMany): void
    {
        $methodName = self::getGetterName($collectionMany);
        $foreignEntity = $collectionMany->getForeignEntity();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $method->addParameter(PHPType::BOOL, 'forceReload', 'false');
        $method->addParameter(PHPType::STRING, 'connectionName', 'null');
        $method->setReturnType($foreignEntity->getInstantiationClassName() . '[]');

        $memberName = '$this->' . $collectionMany->getName();

        // check if member is null or reload is forced then do reload
        $method->addIfStart($memberName . ' === null || $forceReload');
        $method->addCodeLine($memberName . ' = ' . $this->generateCollectionManyGetterSPCall($collectionMany));
        $method->addIfEnd();

        $method->addCodeLine('return ' . $memberName . ';');
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    protected function generateCollectionManyGetterSPCall(CollectionMany $collectionMany): string
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $serviceAccess = $foreignEntity->getServiceAccess();
        $accessMethod = ForeignCollectionManyPlugin::getEntityByReferenceName($collectionMany);

        $parameterList = [];
        foreach ($pkAttributeList as $attribute) {
            $parameterList[] = '$this->' . $attribute->getPhpName();
        }
        $parameterList[] = '$connectionName';
        $invocationSignature = implode(', ', $parameterList);

        return $serviceAccess . '->' . $accessMethod . "($invocationSignature);";
    }

    /**
     * @param CollectionMany $collectionMany
     */
    protected function generateAddToCollection(CollectionMany $collectionMany): void
    {
        $methodName = self::getAddToCollectionName($collectionMany);

        $foreignEntity = $collectionMany->getForeignEntity();
        $foreignClass = $foreignEntity->getInstantiationClassName();
        $foreignReference = $collectionMany->getForeignReference();
        $mappingEntity = $collectionMany->getMappingEntity();
        $mappingReference = $collectionMany->getMappingReference();

        $method = $this->classGenerator->addPublicMethod($methodName);
        $method->setReturnType($mappingEntity->getClassName(), false);

        $method->addParameter($foreignClass, 'entity');


        // create mapping entity
        $method->addCodeLine('$mapping = ' . $mappingEntity->getServiceAccess() . '->' . NewInstancePlugin::METHOD_NEW_INSTANCE . '();');

        // assign both references
        $method->addCodeLine('$mapping->set' . $mappingReference->getMethodName() . '($this);');
        $method->addCodeLine('$mapping->set' . $foreignReference->getMethodName() . '($entity);');

        // add mapping entity to local list
        $method->addCodeLine('$this->' . $collectionMany->getName() . 'Mapping[] = $mapping;');
        $method->addCodeLine('return $mapping;');
    }

    /**
     * @param CollectionMany $collectionMany
     */
    public function generateDeleteFromCollection(CollectionMany $collectionMany): void
    {

        $foreignEntity = $collectionMany->getForeignEntity();
        $methodName = self::getDeleteFromCollectionName($collectionMany);

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);

        $helper->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $helper->addConnectionNameParameter();

        $helper->addConnectionLookup();

        $this->quoteParameter($method, $helper, $collectionMany);

        // add stored procedure invocation
        $spName = StoredProcedureNaming::getDeleteCollectionManyAssignmentName($collectionMany);
        $signature = $this->getInvocationSignature($foreignEntity);
        $helper->addExecuteStoredProcedure($spName, $signature, false);

        $this->generateArraySlice($method, $collectionMany);
    }

    /**
     * @param CollectionMany $collectionMany
     */
    public function generateDeleteAssigned(CollectionMany $collectionMany): void
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        $methodName = self::getDeleteFromAssigned($collectionMany);

        $method = $this->classGenerator->addPublicMethod($methodName);
        $helper = new GeneratorHelper($method);
        $helper->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $helper->addConnectionNameParameter();

        $helper->addConnectionLookup();

        $this->quoteParameter($method, $helper, $collectionMany);

        // add stored procedure invocation
        $spName = StoredProcedureNaming::getDeleteByCollectionManyName($collectionMany);
        $signature = $this->getInvocationSignature($foreignEntity);
        $helper->addExecuteStoredProcedure($spName, $signature, false);

        $this->generateArraySlice($method, $collectionMany);
    }

    /**
     * @param Method $method
     * @param CollectionMany $collectionMany
     */
    protected function generateArraySlice(Method $method, CollectionMany $collectionMany): void
    {
        $foreignEntity = $collectionMany->getForeignEntity();

        $idNullCheck = $this->getIdNullCondition($foreignEntity);

        $collectionMember = '$this->' . $collectionMany->getName();
        $collectionMappingMember = $collectionMember . 'Mapping';

        $method->addIfStart($idNullCheck);
        $method->addCodeLine($collectionMember . ' = [];');
        $method->addCodeLine($collectionMappingMember . ' = [];');
        $method->addCodeLine('return;');
        $method->addIfEnd();

        $method->addIfStart($collectionMember . ' !== null');
        $method->addForeachStart($collectionMember . ' as $index => $entity');
        $compareCondition = $this->getEntityComparePrimaryKeyStatement($foreignEntity);
        $method->addIfStart($compareCondition);
        $method->addCodeLine('array_splice(' . $collectionMember . ', $index, 1);');
        $method->addCodeLine('break;');
        $method->addIfEnd();
        $method->addForeachEnd();
        $method->addIfEnd();

        $method->addIfStart($collectionMappingMember . ' !== null');
        $method->addForeachStart($collectionMappingMember . ' as $index => $mapping');
        $compareCondition = $this->getMappingCompareForeignKey($collectionMany);
        $method->addIfStart($compareCondition);
        $method->addCodeLine('array_splice(' . $collectionMappingMember . ', $index, 1);');
        $method->addCodeLine('break;');
        $method->addIfEnd();
        $method->addForeachEnd();
        $method->addIfEnd();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    protected function getMappingCompareForeignKey(CollectionMany $collectionMany): string
    {
        $foreignReference = $collectionMany->getForeignReference();

        $compareList = [];
        foreach ($foreignReference->getReferenceMappingList() as $mapping) {
            $localAttribute = $mapping->getLocalAttribute();
            $foreignAttribute = $mapping->getForeignAttribute();
            $compareList[] = '$mapping->get' . $localAttribute->getMethodName() . '() === $' . $foreignAttribute->getPhpName();
        }
        return implode(" && ", $compareList);
    }

    /**
     * @param Entity $foreignEntity
     *
     * @return string
     */
    protected function getEntityComparePrimaryKeyStatement(Entity $foreignEntity): string
    {
        $compareList = [];
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $attribute) {
            $name = $attribute->getPhpName();
            $methodName = $attribute->getMethodName();

            $compareList[] = '$' . $name . ' === $entity->get' . $methodName . '()';
        }
        return implode(" && ", $compareList);
    }

    /**
     * @param Entity $foreignEntity
     * @return string
     */
    protected function getIdNullCondition(Entity $foreignEntity): string
    {
        $nullCheck = [];
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $attribute) {
            $nullCheck[] = '$' . $attribute->getPhpName() . ' === null';
        }
        return implode(" && ", $nullCheck);
    }

    /**
     * @param Method $method
     * @param GeneratorHelper $helper
     * @param CollectionMany $collectionMany
     */
    protected function quoteParameter(Method $method, GeneratorHelper $helper, CollectionMany $collectionMany): void
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        $foreignPKList = $foreignEntity->getPrimaryKeyAttributeList();

        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $variableName = $this->geVariableName("local", $this->entity, $pkAttribute);
            $quoteCall = $helper->generateQuoteCall($pkAttribute->getPhpType(), $pkAttribute->getDbType(), '$this->' . $pkAttribute->getPhpName(), $pkAttribute->getIsObject());
            $method->addCodeLine($variableName . ' = ' . $quoteCall . ';');
        }

        foreach ($foreignPKList as $foreignPKAttribute) {
            $variableName = $this->geVariableName("foreign", $foreignEntity, $foreignPKAttribute);
            $quoteCall = $helper->generateQuoteCall($foreignPKAttribute->getPhpType(), $foreignPKAttribute->getDbType(), '$' . $foreignPKAttribute->getPhpName(), $foreignPKAttribute->getIsObject());
            $method->addCodeLine($variableName . ' = ' . $quoteCall . ';');
        }
    }

    /**
     * @param Entity $foreignEntity
     *
     * @return string
     */
    protected function getInvocationSignature(Entity $foreignEntity): string
    {
        $signaturePart = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $signaturePart[] = $this->geVariableName("local", $this->entity, $pkAttribute);
        }
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $signaturePart[] = $this->geVariableName("foreign", $foreignEntity, $pkAttribute);
        }
        return implode(",", $signaturePart);
    }

    /**
     * @param string $prefix
     * @param Entity $foreignEntity
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function geVariableName(string $prefix, Entity $foreignEntity, Attribute $attribute): string
    {
        return '$' . $prefix . $foreignEntity->getTableName() . $attribute->getMethodName();
    }

}