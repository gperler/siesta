<?php
declare(strict_types = 1);

namespace Siesta\Validator;

use Siesta\Contract\ReferenceValidator;
use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\Reference;
use Siesta\Model\ReferenceMapping;
use Siesta\Model\ValidationLogger;
use Siesta\XML\XMLReference;

/**
 * @author Gregor Müller
 */
class DefaultReferenceValidator implements ReferenceValidator
{

    const ERROR_INVALID_FOREIGN_TABLE = "Entity '%s' Reference '%s' points to not defined table %s";

    const ERROR_INVALID_FOREIGN_TABLE_CODE = 1300;

    const ERROR_INVALID_ON_X_VALUE = "Entity '%s' Reference '%s' %s has invalid value '%s'";

    const ERROR_INVALID_ON_X_VALID_CODE = 1301;

    const ERROR_INVALID_LOCAL_REFERENCE = "Entity '%s' Reference '%s' localColumn '%s' can not be resolved";

    const ERROR_INVALID_LOCAL_REFERENCE_CODE = 1302;

    const ERROR_INVALID_FOREIGN_REFERENCE = "Entity '%s' Reference '%s' foreignColumn '%s' can not be resolved";

    const ERROR_INVALID_FOREIGN_REFERENCE_CODE = 1303;

    const ERROR_MAPPING_HAS_NOT_SAME_DB_DATA_TYPE = "Entity '%s' Reference '%s' localColumn and foreignColumn have different data types '%s' and '%s'";

    const ERROR_MAPPING_HAS_NOT_SAME_DB_DATA_TYPE_CODE = 1304;

    const ERROR_MAPPING_HAS_NOT_SAME_PHP_DATA_TYPE = "Entity '%s' Reference '%s' localColumn and foreignColumn have different php types '%s' and '%s'";

    const ERROR_MAPPING_HAS_NOT_SAME_PHP_DATA_TYPE_CODE = 1305;

    const ERROR_NO_REFERENCE_MAPPING = "Entity '%s' Reference '%s' has no referenceMapping.";

    const ERROR_NO_REFERENCE_MAPPING_CODE = 1306;

    const ALLOWED_ON_X = [
        "restrict",
        "cascade",
        "set null",
        "no action"
    ];

    /**
     * @var DataModel
     */
    protected DataModel $dataModel;

    /**
     * @var Entity
     */
    protected Entity $entity;

    /**
     * @var Reference
     */
    protected Reference $reference;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Reference $reference
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, Reference $reference, ValidationLogger $logger): void
    {
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->reference = $reference;
        $this->logger = $logger;

        $this->validateForeignEntity();
        $this->validateOnX($reference->getOnUpdate(), XMLReference::ON_UPDATE);
        $this->validateOnX($reference->getOnDelete(), XMLReference::ON_DELETE);
        $this->validateReferenceMappingList();

    }

    /**
     * @return string|null
     */
    protected function getEntityName(): ?string
    {
        return $this->entity->getClassShortName();
    }

    /**
     * @return string|null
     */
    protected function getReferenceName(): ?string
    {
        return $this->reference->getName();
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
    protected function validateForeignEntity(): void
    {
        if ($this->reference->getForeignEntity() !== null) {
            return;
        }

        $error = sprintf(self::ERROR_INVALID_FOREIGN_TABLE, $this->getEntityName(), $this->getReferenceName(), $this->reference->getForeignTable());
        $this->error($error, self::ERROR_INVALID_FOREIGN_TABLE_CODE);
    }

    /**
     * @param string $onXValue
     * @param string $onX
     */
    protected function validateOnX(string $onXValue, string $onX): void
    {
        if (in_array($onXValue, self::ALLOWED_ON_X)) {
            return;
        }

        $error = sprintf(self::ERROR_INVALID_ON_X_VALUE, $this->getEntityName(), $this->getReferenceName(), $onX, $onXValue);
        $this->error($error, self::ERROR_INVALID_ON_X_VALID_CODE);
    }

    /**
     *
     */
    protected function validateReferenceMappingList(): void
    {
        $referenceMappingList = $this->reference->getReferenceMappingList();

        if (sizeof($referenceMappingList) === 0) {
            $error = sprintf(self::ERROR_NO_REFERENCE_MAPPING, $this->getEntityName(), $this->getReferenceName());
            $this->error($error, self::ERROR_NO_REFERENCE_MAPPING_CODE);
        }
        foreach ($referenceMappingList as $referenceMapping) {
            $this->validateReferenceMapping($referenceMapping);
        }

    }

    /**
     * @param ReferenceMapping $referenceMapping
     */
    protected function validateReferenceMapping(ReferenceMapping $referenceMapping): void
    {
        if ($this->reference->getForeignEntity() === null) {
            return;
        }

        $localAttribute = $referenceMapping->getLocalAttribute();
        if ($localAttribute === null) {
            $error = sprintf(self::ERROR_INVALID_LOCAL_REFERENCE, $this->getEntityName(), $this->getReferenceName(), $referenceMapping->getLocalAttributeName());
            $this->error($error, self::ERROR_INVALID_LOCAL_REFERENCE_CODE);
        }

        $foreignAttribute = $referenceMapping->getForeignAttribute();
        if ($foreignAttribute === null) {
            $error = sprintf(self::ERROR_INVALID_FOREIGN_REFERENCE, $this->getEntityName(), $this->getReferenceName(), $referenceMapping->getForeignAttributeName());
            $this->error($error, self::ERROR_INVALID_FOREIGN_REFERENCE_CODE);
        }

        if ($foreignAttribute !== null && $localAttribute !== null) {
            $this->validateMappingDBDataTypes($localAttribute, $foreignAttribute);
            $this->validateMappingPHPDataTypes($localAttribute, $foreignAttribute);
        }

    }

    /**
     * @param Attribute $localAttribute
     * @param Attribute $foreignAttribute
     */
    protected function validateMappingDBDataTypes(Attribute $localAttribute, Attribute $foreignAttribute): void
    {
        $localType = $localAttribute->getDbType();
        $foreignType = $foreignAttribute->getDbType();

        if ($localType === $foreignType) {
            return;
        }
        $error = sprintf(self::ERROR_MAPPING_HAS_NOT_SAME_DB_DATA_TYPE, $this->getEntityName(), $this->getReferenceName(), $localType, $foreignType);
        $this->error($error, self::ERROR_MAPPING_HAS_NOT_SAME_DB_DATA_TYPE_CODE);
    }

    /**
     * @param Attribute $localAttribute
     * @param Attribute $foreignAttribute
     */
    protected function validateMappingPHPDataTypes(Attribute $localAttribute, Attribute $foreignAttribute): void
    {
        $localType = $localAttribute->getPhpType();
        $foreignType = $foreignAttribute->getPhpType();

        if ($localType === $foreignType) {
            return;
        }
        $error = sprintf(self::ERROR_MAPPING_HAS_NOT_SAME_PHP_DATA_TYPE, $this->getEntityName(), $this->getReferenceName(), $localType, $foreignType);
        $this->error($error, self::ERROR_MAPPING_HAS_NOT_SAME_PHP_DATA_TYPE_CODE);

    }

}