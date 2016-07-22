<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\CodeGenerator\MethodGenerator;
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
    public static function getGetterName(CollectionMany $collectionMany) : string
    {
        return 'get' . $collectionMany->getMethodName();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getAddToCollectionName(CollectionMany $collectionMany) : string
    {
        return 'addTo' . $collectionMany->getMethodName();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getDeleteFromCollectionName(CollectionMany $collectionMany) : string
    {
        return 'deleteFrom' . $collectionMany->getMethodName();
    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    public static function getDeleteFromAssigned(CollectionMany $collectionMany) : string
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        return 'deleteAssigned' . $foreignEntity->getClassShortName();
    }

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useList = [];
        foreach ($entity->getCollectionManyList() as $collectionMany) {
            $mappingEntity = $collectionMany->getMappingEntity();
            $useList[] = $mappingEntity->getClassName();

            $foreignEntity = $collectionMany->getForeignEntity();
            $useList[] = $foreignEntity->getClassName();
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
    protected function generateCollectionManyGetter(CollectionMany $collectionMany)
    {
        $methodName = self::getGetterName($collectionMany);
        $foreignEntity = $collectionMany->getForeignEntity();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter(PHPType::BOOL, 'forceReload', 'false');
        $method->addParameter(PHPType::STRING, 'connectionName', 'null');
        $method->setReturnType($foreignEntity->getInstantiationClassShortName() . '[]');

        $memberName = '$this->' . $collectionMany->getName();

        // check if member is null or reload is forced then do reload
        $method->addIfStart($memberName . ' === null || $forceReload');
        $method->addLine($memberName . ' = ' . $this->generateCollectionManyGetterSPCall($collectionMany));
        $method->addIfEnd();

        $method->addLine('return ' . $memberName . ';');
        $method->end();

    }

    /**
     * @param CollectionMany $collectionMany
     *
     * @return string
     */
    protected function generateCollectionManyGetterSPCall(CollectionMany $collectionMany) : string
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
    protected function generateAddToCollection(CollectionMany $collectionMany)
    {
        $methodName = self::getAddToCollectionName($collectionMany);

        $foreignEntity = $collectionMany->getForeignEntity();
        $foreignClass = $foreignEntity->getInstantiationClassShortName();
        $foreignReference = $collectionMany->getForeignReference();
        $mappingEntity = $collectionMany->getMappingEntity();
        $mappingReference = $collectionMany->getMappingReference();

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addParameter($foreignClass, 'entity');

        // create mapping entity
        $method->addLine('$mapping = ' . $mappingEntity->getServiceAccess() . '->' . NewInstancePlugin::METHOD_NEW_INSTANCE . '();');

        // assign both references
        $method->addLine('$mapping->set' . $mappingReference->getMethodName() . '($this);');
        $method->addLine('$mapping->set' . $foreignReference->getMethodName() . '($entity);');

        // add mapping entity to local list
        $method->addLine('$this->' . $collectionMany->getName() . 'Mapping[] = $mapping;');

        $method->end();
    }

    /**
     * @param CollectionMany $collectionMany
     */
    public function generateDeleteFromCollection(CollectionMany $collectionMany)
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        $methodName = self::getDeleteFromCollectionName($collectionMany);

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $method->addConnectionNameParameter();

        $method->addConnectionLookup();

        $this->quoteParameter($method, $collectionMany);

        // add stored procedure invocation
        $spName = StoredProcedureNaming::getDeleteCollectionManyAssignmentName($collectionMany);
        $signature = $this->getInvocationSignature($foreignEntity);
        $method->addExecuteStoredProcedure($spName, $signature, false);

        $method->end();

    }

    /**
     * @param CollectionMany $collectionMany
     */
    public function generateDeleteAssigned(CollectionMany $collectionMany)
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        $methodName = self::getDeleteFromAssigned($collectionMany);

        $method = $this->codeGenerator->newPublicMethod($methodName);
        $method->addAttributeParameterList($foreignEntity->getPrimaryKeyAttributeList(), 'null');
        $method->addConnectionNameParameter();

        $method->addConnectionLookup();

        $this->quoteParameter($method, $collectionMany);

        // add stored procedure invocation
        $spName = StoredProcedureNaming::getDeleteByCollectionManyName($collectionMany);
        $signature = $this->getInvocationSignature($foreignEntity);
        $method->addExecuteStoredProcedure($spName, $signature, false);

        // done
        $method->end();
    }

    /**
     * @param MethodGenerator $method
     * @param CollectionMany $collectionMany
     */
    protected function quoteParameter(MethodGenerator $method, CollectionMany $collectionMany)
    {
        $foreignEntity = $collectionMany->getForeignEntity();
        $foreignPKList = $foreignEntity->getPrimaryKeyAttributeList();

        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $variableName = $this->geVariableName($this->entity, $pkAttribute);
            $quoteCall = $method->getQuoteCall($pkAttribute->getPhpType(), $pkAttribute->getDbType(), '$this->' . $pkAttribute->getPhpName(), $pkAttribute->getIsObject());
            $method->addLine($variableName . ' = ' . $quoteCall . ';');
        }

        foreach ($foreignPKList as $foreignPKAttribute) {
            $variableName = $this->geVariableName($foreignEntity, $foreignPKAttribute);
            $quoteCall = $method->getQuoteCall($foreignPKAttribute->getPhpType(), $foreignPKAttribute->getDbType(), '$' . $foreignPKAttribute->getPhpName(), $foreignPKAttribute->getIsObject());
            $method->addLine($variableName . ' = ' . $quoteCall . ';');
        }

    }

    /**
     * @param Entity $foreignEntity
     *
     * @return string
     */
    protected function getInvocationSignature(Entity $foreignEntity)
    {
        $signaturePart = [];
        foreach ($this->entity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $signaturePart[] = $this->geVariableName($this->entity, $pkAttribute);
        }
        foreach ($foreignEntity->getPrimaryKeyAttributeList() as $pkAttribute) {
            $signaturePart[] = $this->geVariableName($foreignEntity, $pkAttribute);
        }
        return implode(",", $signaturePart);
    }

    /**
     * @param Entity $foreignEntity
     * @param Attribute $attribute
     *
     * @return string
     */
    protected function geVariableName(Entity $foreignEntity, Attribute $attribute)
    {
        return '$' . lcfirst($foreignEntity->getTableName()) . $attribute->getMethodName();
    }

}