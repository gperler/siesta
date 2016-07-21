<?php
declare(strict_types = 1);

namespace Siesta\GeneratorPlugin\Entity;

use Siesta\CodeGenerator\CodeGenerator;
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
     * @var CodeGenerator
     */
    protected $codeGenerator;

    /**
     * @param Entity $entity
     *
     * @return string[]
     */
    public function getUseClassNameList(Entity $entity) : array
    {
        return [];
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

        $this->generateDeleteMethod();
    }

    /**
     *
     */
    protected function generateDeleteMethod()
    {
        $method = $this->codeGenerator->newPublicMethod(self::METHOD_DELETE);
        $method->addConnectionNameParameter();

        $pkAttributeList = $this->entity->getPrimaryKeyAttributeList();

        $method->addQuoteAttributeList($pkAttributeList, true, true);

        $spName = StoredProcedureNaming::getDeleteByPrimaryKeyName($this->entity);
        $method->addExecuteStoredProcedureWithAttributeList($spName, $pkAttributeList, false);

        $method->addLine('$this->_existing = false;');
        $method->end();
    }

}