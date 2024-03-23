<?php
declare(strict_types=1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
use ReflectionException;
use Siesta\CodeGenerator\GeneratorHelper;
use Siesta\Database\StoredProcedureNaming;
use Siesta\GeneratorPlugin\BasePlugin;
use Siesta\Model\Entity;

/**
 * @author Gregor Müller
 */
class DeletePlugin extends BasePlugin
{

    const METHOD_DELETE = "delete";

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     * @throws ReflectionException
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator): void
    {
        $this->setup($entity, $classGenerator);

        $this->generateDeleteMethod();
    }

    /**
     * @throws ReflectionException
     */
    protected function generateDeleteMethod(): void
    {
        $method = $this->classGenerator->addPublicMethod(self::METHOD_DELETE);
        $helper = new GeneratorHelper($method);

        $helper->addConnectionNameParameter();

        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $helper->addQuoteAttributeList($pkAttributeList, true, true);

        $spName = StoredProcedureNaming::getDeleteByPrimaryKeyName($this->entity);
        $helper->addExecuteStoredProcedureWithAttributeList($spName, $pkAttributeList, false);

        $method->addCodeLine('$this->_existing = false;');
    }

}