<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Nitria\Method;
use Siesta\CodeGenerator\GeneratorHelper;
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
            'Civis\Common\ArrayUtil'
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
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);

        foreach ($this->entity->getStoredProcedureList() as $storedProcedure) {
            $this->generateStoreProcedureCall($storedProcedure);
        }
    }

    /**
     * @param StoredProcedure $storedProcedure
     */
    private function generateStoreProcedureCall(StoredProcedure $storedProcedure)
    {

        $method = $this->classGenerator->addPublicMethod($storedProcedure->getName());

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
    }

    /**
     * @param Method $method
     * @param StoredProcedure $storedProcedure
     */
    private function addParameter(Method $method, StoredProcedure $storedProcedure)
    {
        foreach ($storedProcedure->getParameterList() as $parameter) {
            $method->addParameter($parameter->getPhpType(), $parameter->getName(), 'null');
        }
        $helper = new GeneratorHelper($method);
        $helper->addConnectionNameParameter();
    }

    /**
     * @param Method $method
     * @param StoredProcedure $storedProcedure
     */
    public function addReturnType(Method $method, StoredProcedure $storedProcedure)
    {
        if ($storedProcedure->isResultTypeNone()) {
            return;
        }
        if ($storedProcedure->isResultSetResult()) {
            $method->setReturnType('Siesta\Database\ResultSet');
        }
        $instantiationClass = $this->entity->getInstantiationClassName();
        if ($storedProcedure->isEntityResult()) {
            $method->setReturnType($instantiationClass, true);
        }

        if ($storedProcedure->isListResult()) {
            $method->setReturnType($instantiationClass . '[]', false);
        }
    }

    /**
     * @param Method $method
     * @param StoredProcedure $storedProcedure
     */
    protected function addQuote(Method $method, StoredProcedure $storedProcedure)
    {
        $helper = new GeneratorHelper($method);
        $helper->addConnectionLookup();

        foreach ($storedProcedure->getParameterList() as $parameter) {
            $variableName = '$' . $parameter->getName();
            $helper->addQuoteCall($parameter->getPhpType(), $parameter->getDbType(), $variableName, false, $parameter->getDbLength());
        }
    }

    /**
     * @param Method $method
     * @param StoredProcedure $storedProcedure
     */
    private function generateStoredProcedureSQL(Method $method, StoredProcedure $storedProcedure)
    {
        $dbName = $storedProcedure->getDBName();
        $invocationSignature = $this->generateInvocationSignature($storedProcedure);
        $method->addCodeLine('$spCall = "CALL ' . $dbName . '(' . $invocationSignature . ')";');
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
    private function generateEntityResultType(Method $method)
    {
        $method->addCodeLine('$entityList = $this->' . ExecuteStoredProcedurePlugin::METHOD_EXECUTE_SP . '($spCall);');
        $method->addCodeLine('return ArrayUtil::getFromArray($entityList, 0);');
    }

    /**
     *
     */
    private function generateListResultType(Method $method)
    {
        $method->addCodeLine('return $this->' . ExecuteStoredProcedurePlugin::METHOD_EXECUTE_SP . '($spCall);');
    }

    /**
     *
     */
    private function generateResultSetResultType(Method $method)
    {
        $method->addCodeLine('return $connection->executeStoredProcedure($spCall);');
    }

    /**
     *
     */
    private function generateNoneResultType(Method $method)
    {
        $method->addCodeLine('$connection->execute($spCall);');
    }

}