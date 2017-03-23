<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Nitria\ClassGenerator;
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
     * @var Entity
     */
    protected $entity;

    /**
     * @param Entity $entity
     * @param ClassGenerator $classGenerator
     */
    public function generate(Entity $entity, ClassGenerator $classGenerator)
    {
        $this->setup($entity, $classGenerator);

        $this->generateDeleteMethod();
    }

    /**
     *
     */
    protected function generateDeleteMethod()
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