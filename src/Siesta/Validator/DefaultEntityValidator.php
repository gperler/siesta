<?php
declare(strict_types = 1);

namespace Siesta\Validator;

use Siesta\Contract\EntityValidator;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\ValidationLogger;
use Siesta\Util\ArrayUtil;

/**
 * @author Gregor Müller
 */
class DefaultEntityValidator implements EntityValidator
{

    const ERROR_INVALID_CLASS_NAME = "Entity '%s' has invalid class name '%s'";

    const ERROR_INVALID_CLASS_NAME_CODE = 1100;

    const ERROR_INVALID_NAMESPACE = "Entity '%s' has invalid class namespace '%s'";

    const ERROR_INVALID_NAMESPACE_CODE = 1101;

    const ERROR_INVALID_TABLE = "Entity '%s' has invalid table name '%s'";

    const ERROR_INVALID_TABLE_CODE = 1102;

    const ERROR_DUPLICATE_ATT_REF_COL = "Entity '%s' has duplicate attribute/reference/collection '%s'";

    const ERROR_DUPLICATE_ATT_REF_COL_CODE = 1103;

    const ERROR_DUPLICATE_DB_NAME = "Entity '%s' has duplicate database column '%s'";

    const ERROR_DUPLICATE_DB_NAME_CODE = 1104;

    const ERROR_DUPLICATE_INDEX = "Entity '%s' has duplicate index '%s'";

    const ERROR_DUPLICATE_INDEX_CODE = 1105;

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, ValidationLogger $logger): void
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->logger = $logger;

        $this->validateClassname();
        $this->validateNamespace();
        $this->validateTable();
        $this->validateAttributeReferenceCollectionUnique();
        $this->validateAttributeDBNamesUnique();
        $this->validateIndexNameUnique();

    }

    /**
     * @return string|null
     */
    protected function getEntityName(): ?string
    {
        return $this->entity->getClassShortName();
    }

    /**
     * @param string $text
     * @param int $code
     */
    protected function error(string $text, int $code): void
    {
        $this->logger->error($text, $code);
    }

    /**
     *
     */
    protected function validateClassname(): void
    {
        $className = $this->getEntityName();
        if (preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $className)) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_CLASS_NAME, $className, $className);
        $this->error($error, self::ERROR_INVALID_CLASS_NAME_CODE);

    }

    /**
     *
     */
    protected function validateNamespace(): void
    {
        $namespace = $this->entity->getNamespaceName();
        if (preg_match('/^((?:\\\\{0,1}\\w+|\\w+\\\\{1,2})(?:\\w+\\\\{0,2})+)$/', $namespace) === 1) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_NAMESPACE, $this->getEntityName(), $namespace);
        $this->error($error, self::ERROR_INVALID_NAMESPACE_CODE);
    }

    /**
     *
     */
    protected function validateTable(): void
    {
        $tableName = $this->entity->getTableName();
        if ($tableName !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_TABLE, $this->getEntityName(), $tableName);
        $this->error($error, self::ERROR_INVALID_TABLE_CODE);

    }

    protected function validateAttributeReferenceCollectionUnique(): void
    {
        $duplicateNameList = [];
        $nameList = [];

        foreach ($this->entity->getAttributeList() as $attribute) {
            $this->checkDuplicate($nameList, $duplicateNameList, $attribute->getPhpName());
        }

        foreach ($this->entity->getReferenceList() as $reference) {
            $this->checkDuplicate($nameList, $duplicateNameList, $reference->getName());
        }

        foreach ($this->entity->getCollectionList() as $collection) {
            $this->checkDuplicate($nameList, $duplicateNameList, $collection->getName());
        }

        foreach ($this->entity->getCollectionManyList() as $collectionMany) {
            $this->checkDuplicate($nameList, $duplicateNameList, $collectionMany->getName());
        }

        foreach ($duplicateNameList as $duplicateName) {
            $error = sprintf(self::ERROR_DUPLICATE_ATT_REF_COL, $this->getEntityName(), $duplicateName);
            $this->error($error, self::ERROR_DUPLICATE_ATT_REF_COL_CODE);
        }
    }

    /**
     *
     */
    protected function validateAttributeDBNamesUnique(): void
    {

        $duplicateNameList = [];
        $nameList = [];
        foreach ($this->entity->getAttributeList() as $attribute) {
            $this->checkDuplicate($nameList, $duplicateNameList, $attribute->getDBName());
        }

        foreach ($duplicateNameList as $duplicateName) {
            $error = sprintf(self::ERROR_DUPLICATE_DB_NAME, $this->getEntityName(), $duplicateName);
            $this->error($error, self::ERROR_DUPLICATE_DB_NAME_CODE);
        }

    }

    /**
     *
     */
    protected function validateIndexNameUnique(): void
    {
        $duplicateNameList = [];
        $nameList = [];
        foreach ($this->entity->getIndexList() as $index) {
            $this->checkDuplicate($nameList, $duplicateNameList, $index->getName());
        }
        foreach ($duplicateNameList as $duplicateName) {
            $error = sprintf(self::ERROR_DUPLICATE_INDEX, $this->getEntityName(), $duplicateName);
            $this->error($error, self::ERROR_DUPLICATE_INDEX_CODE);
        }
    }

    /**
     * @param array $nameList
     * @param array $duplicateNameList
     * @param $name
     */
    protected function checkDuplicate(array &$nameList, array &$duplicateNameList, $name): void
    {
        $existing = ArrayUtil::getFromArray($nameList, $name);
        if ($existing) {
            $duplicateNameList[] = $name;
        }
        $nameList[$name] = true;
    }
}