<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Nitria\ClassGenerator;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\GeneratorPlugin\Entity\FromResultSetPlugin;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;

/**
 * @author Gregor Müller
 */
class ExecuteStoredProcedurePlugin extends BasePlugin
{

    const METHOD_CREATE_FROM_RESULT_SET = "createInstanceFromResultSet";

    const METHOD_EXECUTE_SP = "executeStoredProcedure";

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [
            'Siesta\Database\ConnectionFactory'
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
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);
        $this->generateCreateInstanceFromResultSet();
        $this->generateExecuteProcedure();

    }

    /**
     *
     */
    protected function generateCreateInstanceFromResultSet()
    {
        $returnType = $this->entity->getInstantiationClassName();

        $method = $this->classGenerator->addPublicMethod(self::METHOD_CREATE_FROM_RESULT_SET);
        $method->addParameter('Siesta\Database\ResultSet', 'resultSet');
        $method->setReturnType($returnType);

        $method->addCodeLine('$entity = $this->' . NewInstancePlugin::METHOD_NEW_INSTANCE . '();');
        $method->addCodeLine('$entity->' . FromResultSetPlugin::METHOD_FROM_RESULT_SET . '($resultSet);');
        $method->addCodeLine('return $entity;');
    }

    /**
     *
     */
    protected function generateExecuteProcedure()
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_EXECUTE_SP);
        $helper = new GeneratorHelper($method);

        $method->addParameter(PHPType::STRING, 'spCall');
        $helper->addConnectionNameParameter();
        $method->setReturnType($this->entity->getInstantiationClassName() . '[]',false);

        $helper->addConnectionLookup();

        // initialize result array and execute stored procedure
        $method->addCodeLine('$entityList = [];');
        $method->addCodeLine('$resultSet = $connection->executeStoredProcedure($spCall);');

        // iterate result set and instantiate new entities
        $method->addWhileStart('$resultSet->hasNext()');
        $method->addCodeLine('$entityList[] = $this->' . self::METHOD_CREATE_FROM_RESULT_SET . '($resultSet);');
        $method->addWhileEnd();

        // close result set and done
        $method->addCodeLine('$resultSet->close();');
        $method->addCodeLine('return $entityList;');
    }

}
