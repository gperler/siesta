<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 15.10.15
 * Time: 22:46
 */

namespace siestaphp\migrator;

use siestaphp\datamodel\entity\EntityGeneratorSource;
use siestaphp\datamodel\entity\EntitySource;
use siestaphp\driver\ColumnMigrator;

/**
 * Class EntityMigrator
 * @package siestaphp\migrator
 */
class EntityMigrator
{

    /**
     * @var ColumnMigrator
     */
    protected $columnMigrator;

    /**
     * @var EntitySource
     */
    protected $databaseEntity;

    /**
     * @var EntityGeneratorSource
     */
    protected $modelEntity;

    /**
     * @var string[]
     */
    protected $statementList;

    /**
     * @param ColumnMigrator $migrator
     * @param EntitySource $databaseEntity
     * @param EntityGeneratorSource $modelEntity
     */
    public function __construct(ColumnMigrator $migrator, EntitySource $databaseEntity, EntityGeneratorSource $modelEntity)
    {
        $this->columnMigrator = $migrator;
        $this->databaseEntity = $databaseEntity;
        $this->modelEntity = $modelEntity;
        $this->statementList = array();
    }

    /**
     * @return string[]
     */
    public function createAlterStatementList()
    {
        $this->migratePrimaryKey();
        $this->migrateAttributeList();
        $this->migrateReferenceList();
        $this->migrateIndexList();
        return $this->statementList;
    }

    private function migratePrimaryKey()
    {
        // ALTER TABLE `Author` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`);
    }

    private function migrateAttributeList()
    {

        $attributeListMigrator = new AttributeListMigrator($this->columnMigrator, $this->databaseEntity->getAttributeSourceList(), $this->modelEntity->getAttributeGeneratorSourceList());
        $statementList = $attributeListMigrator->createAlterStatementList();

        $this->addStatementList($statementList);
    }

    private function migrateReferenceList()
    {
        $referenceListMigrator = new ReferenceListMigrator($this->columnMigrator, $this->databaseEntity->getReferenceSourceList(), $this->modelEntity->getReferenceGeneratorSourceList());
        $statementList = $referenceListMigrator->createAlterStatementList();
        $this->addStatementList($statementList);
    }

    private function migrateIndexList()
    {

    }

    /**
     * @param string[] $statementList
     */
    private function addStatementList($statementList)
    {
        foreach ($statementList as $statement) {
            $this->statementList[] = str_replace(ColumnMigrator::TABLE_PLACE_HOLDER, $this->databaseEntity->getTable(), $statement);

            // handle delimited table
            if ($this->databaseEntity->isDelimit()) {

            }
        }
    }

}