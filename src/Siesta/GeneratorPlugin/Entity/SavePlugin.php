<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\CodeGenerator\MethodGenerator;
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
            'Siesta\Util\DefaultCycleDetector',
            'Siesta\Contract\CycleDetector'
        ];
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
        $this->generateCreateSPCall();
        $this->generateSaveMethod();
    }

    /**
     *
     */
    protected function generateSaveMethod()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_SAVE);
        $method->addParameter(PHPType::BOOL, 'cascade', 'false');
        $method->addParameter('CycleDetector', 'cycleDetector', 'null');
        $method->addConnectionNameParameter();

        $method->addConnectionLookup();

        $method->newLine();

        $this->generateCycleDetection($method);

        $this->generateReferenceSave($method);

        $this->generateEntitySave($method);

        $this->generateCollectionSave($method);

        $this->generateCollectionManySave($method);

        $method->end();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateCycleDetection(MethodGenerator $method)
    {
        $method->addIfStart('$cycleDetector === null');
        $method->addLine('$cycleDetector = new DefaultCycleDetector();');
        $method->addIfEnd();
        $method->newLine();

        // canProceed
        $method->addIfStart('!$cycleDetector->canProceed(self::TABLE_NAME, $this)');
        $method->addLine('return;');
        $method->addIfEnd();
        $method->newLine();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateReferenceSave(MethodGenerator $method)
    {
        foreach ($this->entity->getReferenceList() as $reference) {
            $name = $reference->getName();
            $memberName = '$this->' . $reference->getName();
            $method->addIfStart('$cascade && ' . $memberName . ' !== null');
            $method->addLine($memberName . '->' . self::METHOD_SAVE . '($cascade, $cycleDetector, $connectionName);');
            $method->addIfEnd();
            $method->newLine();
        }
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateEntitySave(MethodGenerator $method)
    {
        $method->addLine('$call = $this->' . self::METHOD_CREATE_SP_CALL_STATEMENT . '($connectionName);');
        $method->addLine('$connection->execute($call);');
        $method->addLine('$this->_existing = true;');
        $method->newLine();

        $method->addIfStart('!$cascade');
        $method->addLine('return;');
        $method->addIfEnd();
        $method->newLine();
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateCollectionSave(MethodGenerator $method)
    {
        foreach ($this->entity->getCollectionList() as $collection) {
            $method->addIfStart('$this->' . $collection->getName() . ' !== null');
            $method->addForeachStart('$this->' . $collection->getName() . ' as $entity');
            $method->addLine('$entity->save($cascade, $cycleDetector, $connectionName);');
            $method->addForeachEnd();
            $method->addIfEnd();
            $method->newLine();
        }
    }

    /**
     * @param MethodGenerator $method
     */
    protected function generateCollectionManySave(MethodGenerator $method)
    {
        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $method->addForeachStart('$this->' . $collectionMany->getName() . 'Mapping as $mapping');
            $method->addLine('$mapping->save($cascade, $cycleDetector, $connectionName);');
            $method->addForeachEnd();
        }
    }

    /**
     *
     */
    protected function generateCreateSPCall()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_CREATE_SP_CALL_STATEMENT);
        $method->addConnectionNameParameter();
        $method->setReturnType(PHPType::STRING);

        // choose stored procedure
        $insertName = StoredProcedureNaming::getSPInsertName($this->entity);
        $updateName = StoredProcedureNaming::getUpdateName($this->entity);
        $method->addLine('$spCall = ($this->_existing) ? "CALL ' . $updateName . '(" : "CALL ' . $insertName . '(";');

        $method->addConnectionLookup();

        $this->createPrimaryKeyLookup($method);

        $escapeList = $this->getQuoteCallList($method);
        $line = 'return $spCall . ' . implode(" . ',' . ", $escapeList) . " . ');';";

        $method->addLine($line);
        $method->end();

    }

    /**
     * @param MethodGenerator $method
     */
    protected function createPrimaryKeyLookup(MethodGenerator $method)
    {
        foreach ($this->entity->getPrimaryKeyAttributeList() as $attribute) {
            $method->addLine('$this->get' . $attribute->getMethodName() . '(true, $connectionName);');
        }
    }

    /**
     * @return string[]
     */
    protected function getQuoteCallList(MethodGenerator $method) : array
    {
        $list = [];
        foreach ($this->entity->getAttributeList() as $attribute) {
            if ($attribute->getIsTransient()) {
                continue;
            }

            $memberName = '$this->' . $attribute->getPhpName();
            $list[] = $method->getQuoteCall($attribute->getPhpType(), $attribute->getDbType(), $memberName, $attribute->getIsObject());
        }
        return $list;
    }

}