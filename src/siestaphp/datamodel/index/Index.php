<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 28.09.15
 * Time: 22:23
 */

namespace siestaphp\datamodel\index;

use Codeception\Util\Debug;
use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\entity\Entity;
use siestaphp\datamodel\Processable;
use siestaphp\generator\GeneratorLog;

/**
 * Class Index
 * @package siestaphp\datamodel\index
 */
class Index implements Processable, IndexSource, IndexDatabaseSource
{

    /**
     * @var IndexSource
     */
    protected $indexSource;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var IndexPart[]
     */
    protected $indexPartList;

    /**
     * @param Entity $entity
     * @param IndexSource $indexSource
     */
    public function __construct(Entity $entity, IndexSource $indexSource)
    {
        $this->indexSource = $indexSource;
        $this->entity = $entity;
        $this->extractIndexPartList();
    }

    private function extractIndexPartList()
    {
        $this->indexPartList = array();
        foreach ($this->indexSource->getIndexPartSourceList() as $indexPartSource) {
            $this->indexPartList[] = new IndexPart($this->entity, $this, $indexPartSource);
        }
    }

    /**
     * @return IndexPartDatabaseSource[]
     */
    public function getIndexDatabaseSourceList() {
        return $this->indexPartList;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->indexSource->getName();
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return $this->indexSource->isUnique();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->indexSource->getType();
    }

    /**
     * @return IndexPartSource[]
     */
    public function getIndexPartSourceList()
    {
        return $this->indexPartList;
    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->updateModel($container);
        }
    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {
        foreach ($this->indexPartList as $indexPart) {
            $indexPart->validate($log);
        }
    }

}