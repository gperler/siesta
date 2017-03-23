<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use Nitria\Method;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

/**
 * @author Gregor Müller
 */
class SavePlugin extends BasePlugin
{

    const METHOD_CREATE_SP_CALL_STATEMENT = "createSaveStoredProcedureCall";

    const METHOD_SAVE = "save";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [
            'Siesta\Database\Escaper',
            'Siesta\Database\ConnectionFactory',
            'Siesta\Util\DefaultCycleDetector'
        ];
    }

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);
        $this->generateCreateSPCall();
        $this->generateSaveMethod();
    }

    /**
     *
     */
    protected function generateSaveMethod()
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_SAVE);
        $helper = new GeneratorHelper($method);

        $method->addParameter(PHPType::BOOL, 'cascade', 'false');
        $method->addParameter('Siesta\Contract\CycleDetector', 'cycleDetector', 'null');
        $helper->addConnectionNameParameter();

        $helper->addConnectionLookup();

        $method->addNewLine();

        $this->generateCycleDetection($method);

        $this->generateReferenceSave($method);

        $this->generateEntitySave($method);

        $this->generateCollectionSave($method);

        $this->generateCollectionManySave($method);
    }

    /**
     * @param Method $method
     */
    protected function generateCycleDetection(Method $method)
    {
        $method->addIfStart('$cycleDetector === null');
        $method->addCodeLine('$cycleDetector = new DefaultCycleDetector();');
        $method->addIfEnd();
        $method->addNewLine();

        // canProceed
        $method->addIfStart('!$cycleDetector->canProceed(self::TABLE_NAME, $this)');
        $method->addCodeLine('return;');
        $method->addIfEnd();
        $method->addNewLine();
    }

    /**
     * @param Method $method
     */
    protected function generateReferenceSave(Method $method)
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $memberName = '$this->' . $reference->getName();
            $method->addIfStart('$cascade && ' . $memberName . ' !== null');
            $method->addCodeLine($memberName . '->' . self::METHOD_SAVE . '($cascade, $cycleDetector, $connectionName);');
            $method->addIfEnd();
            $method->addNewLine();
        }
    }

    /**
     * @param Method $method
     */
    protected function generateEntitySave(Method $method)
    {
        $method->addCodeLine('$call = $this->' . self::METHOD_CREATE_SP_CALL_STATEMENT . '($connectionName);');
        $method->addCodeLine('$connection->execute($call);');
        $method->addCodeLine('$this->_existing = true;');
        $method->addNewLine();

        $method->addIfStart('!$cascade');
        $method->addCodeLine('return;');
        $method->addIfEnd();
        $method->addNewLine();
    }

    /**
     * @param Method $method
     */
    protected function generateCollectionSave(Method $method)
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $method->addIfStart('$this->' . $collection->getName() . ' !== null');
            $method->addForeachStart('$this->' . $collection->getName() . ' as $entity');
            $method->addCodeLine('$entity->save($cascade, $cycleDetector, $connectionName);');
            $method->addForeachEnd();
            $method->addIfEnd();
            $method->addNewLine();
        }
    }

    /**
     * @param Method $method
     */
    protected function generateCollectionManySave(Method $method)
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $method->addForeachStart('$this->' . $collectionMany->getName() . 'Mapping as $mapping');
            $method->addCodeLine('$mapping->save($cascade, $cycleDetector, $connectionName);');
            $method->addForeachEnd();
        }
    }

    /**
     *
     */
    protected function generateCreateSPCall()
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_CREATE_SP_CALL_STATEMENT);
        $helper = new GeneratorHelper($method);

        $helper->addConnectionNameParameter();
        $method->setReturnType(PHPType::STRING);

        // choose stored procedure
        $insertName = StoredProcedureNaming::getSPInsertName($this->entity);
        $updateName = StoredProcedureNaming::getUpdateName($this->entity);
        $method->addCodeLine('$spCall = ($this->_existing) ? "CALL ' . $updateName . '(" : "CALL ' . $insertName . '(";');

        $helper->addConnectionLookup();

        $this->createPrimaryKeyLookup($method);

        $escapeList = $this->getQuoteCallList($helper);
        $line = 'return $spCall . ' . implode(" . ',' . ", $escapeList) . " . ');';";

        $method->addCodeLine($line);
    }

    /**
     * @param Method $method
     */
    protected function createPrimaryKeyLookup(Method $method)
    {
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $method->addCodeLine('$this->get' . $attribute->getMethodName() . '(true, $connectionName);');
        }
    }

    /**
     * @return string[]
     */
    protected function getQuoteCallList(GeneratorHelper $helper) : array
    {
        $list = [];
        foreach ($this->entity->getAttributeList() as $attribute) {
            if ($attribute->getIsTransient()) {
                continue;
            }

            $memberName = '$this->' . $attribute->getPhpName();
            $list[] = $helper->generateQuoteCall($attribute->getPhpType(), $attribute->getDbType(), $memberName, $attribute->getIsObject());
        }
        return $list;
    }

}