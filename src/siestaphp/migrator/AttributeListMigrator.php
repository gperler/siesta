<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 14.10.15
 * Time: 11:34
 */

namespace siestaphp\migrator;

use siestaphp\datamodel\attribute\AttributeGeneratorSource;
use siestaphp\datamodel\attribute\AttributeSource;
use siestaphp\driver\ColumnMigrator;

/**
 * Class AttributeListMigrator
 * @package siestaphp\migrator
 */
class AttributeListMigrator
{

    /**
     * @var AttributeSource[] $databaseAttributeList
     */
    protected $databaseAttributeList;

    /**
     * @var AttributeGeneratorSource[]
     */
    protected $modelAttributeList;

    /**
     * @var ColumnMigrator
     */
    protected $columnMigrator;

    /**
     * @var string[]
     */
    protected $addStatementList;

    /**
     * @var string[]
     */
    protected $modifiyStatementList;

    /**
     * @var string[]
     */
    protected $dropStatementList;

    /**
     * @param ColumnMigrator $columnMigrator
     * @param AttributeSource[] $databaseAttributeList
     * @param AttributeGeneratorSource[] $modelAttributeList
     */
    public function __construct(ColumnMigrator $columnMigrator, $databaseAttributeList, $modelAttributeList)
    {
        $this->columnMigrator = $columnMigrator;
        $this->databaseAttributeList = $databaseAttributeList;
        $this->modelAttributeList = $modelAttributeList;
        $this->addStatementList = array();
        $this->modifiyStatementList = array();
        $this->dropStatementList = array();
    }

    /**
     *
     */
    public function createAlterStatementList()
    {
        $processedDatabaseList = array();

        // iterate attributes from model and retrieve alter statements
        foreach ($this->modelAttributeList as $modelAttribute) {
            // check if a corresponding database attribute exists
            $databaseAttribute = $this->getAttributeSourceByDatabaseName($this->databaseAttributeList, $modelAttribute->getDatabaseName());

            // retrieve alter statements from migrator and add them
            $this->createAlterStatement($databaseAttribute, $modelAttribute);

            // if a database attribute has been found add it to the processed list
            if ($databaseAttribute) {
                $processedDatabaseList[] = $databaseAttribute->getDatabaseName();
            }
        }

        // iterate attributes from database and retrieve alter statements
        foreach ($this->databaseAttributeList as $databaseAttribute) {

            // check if attribute has already been processed
            if (in_array($databaseAttribute->getDatabaseName(), $processedDatabaseList)) {
                continue;
            }
            // no corresponding model attribute will result in drop statements
            $this->createAlterStatement($databaseAttribute, null);
        }

    }

    /**
     * @param AttributeSource[] $attributeSourceList
     * @param string $databaseName
     *
     * @return AttributeSource|null
     */
    private function getAttributeSourceByDatabaseName($attributeSourceList, $databaseName)
    {
        foreach ($attributeSourceList as $attribute) {
            if ($attribute->getDatabaseName() === $databaseName) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @param AttributeSource $asIs
     * @param AttributeGeneratorSource $toBe
     *
     * @return void
     */
    private function createAlterStatement($asIs, $toBe)
    {
        // no as-is create the column
        if ($asIs === null) {
            $this->addStatementList[] = $this->columnMigrator->createAddColumnStatement($toBe);
            return;
        }

        // no to-be drop the column
        if ($toBe === null) {
            $this->dropStatementList[] = $this->columnMigrator->createDropColumnStatement($asIs->getDatabaseName());
            return;
        }

        // types identical nothing to do
        if ($asIs->getDatabaseType() === $toBe->getDatabaseType() and $asIs->isRequired() === $toBe->isRequired()) {
            return;
        }

        // types not identical
        $this->modifiyStatementList[] = $this->columnMigrator->createModifiyColumnStatement($toBe);
    }

    /**
     * @return string[]
     */
    public function getAddStatementList() {
        return $this->addStatementList;
    }

    /**
     * @return string[]
     */
    public function getModifyStatementList() {
        return $this->modifiyStatementList;
    }

    /**
     * @return string[]
     */
    public function getDropStatementList() {
        return $this->dropStatementList;
    }
}