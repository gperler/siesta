<?php

declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\ServiceClass;

use Siesta\CodeGenerator\CodeGenerator;
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
            'Siesta\Database\ResultSet',
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
     * @param CodeGenerator $codeGenerator
     */
    public function generate(Entity $entity, CodeGenerator $codeGenerator)
    {
        $this->setup($entity, $codeGenerator);
        $this->generateCreateInstanceFromResultSet();
        $this->generateExecuteProcedure();

    }

    /**
     *
     */
    protected function generateCreateInstanceFromResultSet()
    {
        $returnType = $this->entity->getInstantiationClassShortName();

        $method = $this->codeGenerator->newPublicMethod(self::METHOD_CREATE_FROM_RESULT_SET);
        $method->addParameter('ResultSet', 'resultSet');
        $method->setReturnType($returnType);

        $method->addLine('$entity = $this->' . NewInstancePlugin::METHOD_NEW_INSTANCE . '();');
        $method->addLine('$entity->' . FromResultSetPlugin::METHOD_FROM_RESULT_SET . '($resultSet);');
        $method->addLine('return $entity;');

        $method->end();
    }

    /**
     *
     */
    protected function generateExecuteProcedure()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_EXECUTE_SP);
        $method->addParameter(PHPType::STRING, 'spCall');
        $method->addConnectionNameParameter();
        $method->setReturnType($this->entity->getInstantiationClassShortName() . '[]');

        $method->addConnectionLookup();

        // initialize result arry and execute stored procedure
        $method->addLine('$entityList = [];');
        $method->addLine('$resultSet = $connection->executeStoredProcedure($spCall);');

        // iterate result set and instantiate new entities
        $method->addWhileStart('$resultSet->hasNext()');
        $method->addLine('$entityList[] = $this->' . self::METHOD_CREATE_FROM_RESULT_SET . '($resultSet);');
        $method->addWhileEnd();

        // close result set and done
        $method->addLine('$resultSet->close();');
        $method->addLine('return $entityList;');

        $method->end();
    }

}
