<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
use Siesta\CodeGenerator\MethodGenerator;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;
use Siesta\Model\StoredProcedure;

/**
 * @author Gregor Müller
 */
class StoredProcedurePlugin extends BasePlugin
{
    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        $useList = [
            'Siesta\Database\Escaper',
            'Siesta\Database\ConnectionFactory',
            'Siesta\Util\ArrayUtil'
        ];

        foreach ($entity->getStoredProcedureList() as $storedProcedure) {
            foreach ($storedProcedure->getParameterList() as $parameter) {
                if ($parameter->getPhpType() === PHPType::SIESTA_DATE_TIME) {
                    $useList[] = 'Siesta\Util\SiestaDateTime';
                }
            }
        }
        return $useList;
    }

    /**
     * @return string[]
     */
    public function getDependantPluginList() : array
    {
        return [
            'Siesta\GeneratorPlugin\ServiceClass\ExecuteStoredProcedurePlugin'
        ];
    }

    /**
     * @param Entity $entity
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);

        foreach ($this->entity->getStoredProcedureList() as $storedProcedure) {
            $this->generateStoreProcedureCall($storedProcedure);
        }
    }

    /**
     * @param StoredProcedure $storedProcedure
     */
    private function generateStoreProcedureCall(StoredProcedure $storedProcedure)
    {

        $method = $this->codeGenerator->newPublicMethod($storedProcedure->getName());

        $this->addParameter($method, $storedProcedure);
        $this->addReturnType($method, $storedProcedure);

        $this->addQuote($method, $storedProcedure);

        $this->generateStoredProcedureSQL($method, $storedProcedure);

        if ($storedProcedure->isEntityResult()) {
            $this->generateEntityResultType($method);
        }

        if ($storedProcedure->isListResult()) {
            $this->generateListResultType($method);
        }

        if ($storedProcedure->isResultSetResult()) {
            $this->generateResultSetResultType($method);
        }

        if ($storedProcedure->isResultTypeNone()) {
            $this->generateNoneResultType($method);
        }

        $method->end();
    }

    /**
     * @param MethodGenerator $method
     * @param StoredProcedure $storedProcedure
     */
    private function addParameter(MethodGenerator $method, StoredProcedure $storedProcedure)
    {
        foreach ($storedProcedure->getParameterList() as $parameter) {
            $method->addParameter($parameter->getPhpType(), $parameter->getName(), 'null');
        }
        $method->addConnectionNameParameter();
    }

    /**
     * @param MethodGenerator $method
     * @param StoredProcedure $storedProcedure
     */
    public function addReturnType(MethodGenerator $method, StoredProcedure $storedProcedure)
    {
        if ($storedProcedure->isResultTypeNone()) {
            return;
        }
        if ($storedProcedure->isResultSetResult()) {
            $method->setReturnType('ResultSet');
        }
        $instantiationClass = $this->entity->getInstantiationClassShortName();
        if ($storedProcedure->isEntityResult()) {
            $method->setReturnType($instantiationClass, true);
        }

        if ($storedProcedure->isListResult()) {
            $method->setReturnType($instantiationClass . '[]');
        }
    }

    /**
     * @param MethodGenerator $method
     * @param StoredProcedure $storedProcedure
     */
    protected function addQuote(MethodGenerator $method, StoredProcedure $storedProcedure)
    {
        $method->addConnectionLookup();

        foreach ($storedProcedure->getParameterList() as $parameter) {
            $variableName = '$' . $parameter->getName();
            $quoteCall = $variableName . ' = ' . $method->getQuoteCall($parameter->getPhpType(), $parameter->getDbType(), $variableName, false, $parameter->getDbLength()) . ';';
            $method->addLine($quoteCall);
        }
    }

    /**
     * @param MethodGenerator $method
     * @param StoredProcedure $storedProcedure
     */
    private function generateStoredProcedureSQL(MethodGenerator $method, StoredProcedure $storedProcedure)
    {
        $dbName = $storedProcedure->getDBName();
        $invocationSignature = $this->generateInvocationSignature($storedProcedure);
        $method->addLine('$spCall = "CALL ' . $dbName . '(' . $invocationSignature . ')";');
    }

    /**
     * @param StoredProcedure $storedProcedure
     *
     * @return string
     */
    private function generateInvocationSignature(StoredProcedure $storedProcedure)
    {
        $paramList = [];
        foreach ($storedProcedure->getParameterList() as $parameter) {
            $paramList[] = '$' . $parameter->getName();
        }
        return implode(",", $paramList);
    }

    /**
     *
     */
    private function generateEntityResultType(MethodGenerator $method)
    {
        $method->addLine('$entityList = $this->' . ExecuteStoredProcedurePlugin::METHOD_EXECUTE_SP . '($spCall);');
        $method->addLine('return ArrayUtil::getFromArray($entityList, 0);');
    }

    /**
     *
     */
    private function generateListResultType(MethodGenerator $method)
    {
        $method->addLine('return $this->' . ExecuteStoredProcedurePlugin::METHOD_EXECUTE_SP . '($spCall);');
    }

    /**
     *
     */
    private function generateResultSetResultType(MethodGenerator $method)
    {
        $method->addLine('return $connection->executeStoredProcedure($spCall);');
    }

    /**
     *
     */
    private function generateNoneResultType(MethodGenerator $method)
    {
        $method->addLine('$connection->execute($spCall);');
    }

}