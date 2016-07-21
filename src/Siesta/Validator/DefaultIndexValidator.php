<?php
declare(strict_types = 1);

namespace Siesta\Validator;

use Siesta\Contract\IndexValidator;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Index;
use Siesta\Model\IndexPart;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
class DefaultIndexValidator implements IndexValidator
{
    const ERROR_INVALID_INDEX_NAME = "Entity '%s' Index '%s' has invalid name.";

    const ERROR_INVALID_INDEX_NAME_CODE = 1400;

    const ERROR_INVALID_COLUMN = "Entity '%s' Index '%s' refers to non existing column '%s'";

    const ERROR_INVALID_COLUMN_CODE = 1401;

    const ERROR_NO_INDEX_PART = "Entity '%s' Index '%s' has no indexPart.";

    const ERROR_NO_INDEX_PART_CODE = 1402;

    /**
     * @var DataModel
     */
    protected $datamodel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Index
     */
    protected $index;

    /**
     * @var ValidationLogger
     */
    protected $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Index $index
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, Index $index, ValidationLogger $logger)
    {
        $this->logger = $logger;
        $this->datamodel = $dataModel;
        $this->entity = $entity;
        $this->index = $index;

        $this->validateIndexName();
        $this->validateIndexPartList();
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return $this->entity->getClassShortName();
    }

    /**
     * @return string
     */
    protected function getIndexName()
    {
        return $this->index->getName();
    }

    /**
     * @param string $text
     * @param int $code
     */
    protected function error(string $text, int $code)
    {
        $this->logger->error($text, $code);
    }

    /**
     *
     */
    protected function validateIndexName()
    {
        $indexName = $this->getIndexName();
        if ($indexName !== null) {
            return;
        }

        $error = sprintf(self::ERROR_INVALID_INDEX_NAME, $this->getEntityName(), $indexName);
        $this->error($error, self::ERROR_INVALID_INDEX_NAME_CODE);
    }

    protected function validateIndexPartList()
    {
        $indexPartList = $this->index->getIndexPartList();
        if (sizeof($indexPartList) === 0) {
            $error = sprintf(self::ERROR_NO_INDEX_PART, $this->getEntityName(), $this->getIndexName());
            $this->error($error, self::ERROR_NO_INDEX_PART_CODE);
        }

        foreach ($this->index->getIndexPartList() as $indexPart) {
            $this->validateIndexPart($indexPart);
        }
    }

    /**
     * @param IndexPart $indexPart
     */
    protected function validateIndexPart(IndexPart $indexPart)
    {
        if ($indexPart->getAttribute() !== null) {
            return;
        }

        $error = sprintf(self::ERROR_INVALID_COLUMN, $this->getEntityName(), $this->getIndexName(), $indexPart->getColumnName());
        $this->error($error, self::ERROR_INVALID_COLUMN_CODE);

    }

}