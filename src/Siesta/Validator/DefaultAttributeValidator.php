<?php
declare(strict_types=1);

namespace Siesta\Validator;

use Siesta\Contract\AttributeValidator;
use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
use Siesta\Model\PHPType;
use Siesta\Model\ValidationLogger;

/**
 * @author Gregor Müller
 */
class DefaultAttributeValidator implements AttributeValidator
{

    const ERROR_INVALID_NAME = "Entity '%s' Attribute with invalid name '%s' found.";

    const ERROR_INVALID_NAME_CODE = 1200;

    const ERROR_NO_TYPE = "Entity '%s' Attribute '%s' does not have a type. Available types: '%s'";

    const ERROR_NO_TYPE_CODE = 1201;

    const ERROR_INVALID_TYPE = "Entity '%s' Attribute '%s' type '%s' interpreted as class, but class does not exist. Available types: %s";

    const ERROR_INVALID_TYPE_CODE = 1202;

    const ERROR_INVALID_AUTO_VALUE = "Entity '%s' Attribute '%s' has invalid auto value '%s'. Available values %s";

    const ERROR_INVALID_AUTO_VALUE_CODE = 1203;

    const ERROR_NO_DB_TYPE = "Entity '%s' Attribute '%s' is not transient and has no dbType.";

    const ERROR_NO_DB_TYPE_CODE = 1204;

    const WARN_NO_AUTO_VALUE = "Entity '%s' Attribute '%s' is primary key but does not have an auto value.";

    const WARN_NO_AUTO_VALUE_CODE = 1205;

    const AUTO_VALUE_UUID = "uuid";

    const AUTO_VALUE_AUTOINCREMENT = "autoincrement";

    const ALLOWED_AUTO_VALUE = [
        null,
        self::AUTO_VALUE_AUTOINCREMENT,
        self::AUTO_VALUE_UUID
    ];

    const PHP_TYPE_LIST = [
        PHPType::BOOL,
        PHPType::INT,
        PHPType::FLOAT,
        PHPType::STRING,
        PHPType::SIESTA_DATE_TIME,
        PHPType::ARRAY
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
     * @var Attribute
     */
    protected Attribute $attribute;

    /**
     * @var ValidationLogger
     */
    protected ValidationLogger $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Attribute $attribute
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, Attribute $attribute, ValidationLogger $logger): void
    {
        $this->logger = $logger;
        $this->dataModel = $dataModel;
        $this->entity = $entity;
        $this->attribute = $attribute;

        $this->validateName();
        $this->validateType();
        $this->validateAutoValue();
        $this->validateObjectType();
        $this->validateNonTransient();
        $this->validateWarnAutoValue();
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return $this->entity->getClassShortName();
    }

    /**
     * @return string
     */
    protected function getAttributeName(): string
    {
        return $this->attribute->getPhpName();
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
    protected function validateName(): void
    {
        if ($this->attribute->getPhpName() !== null) {
            return;
        }
        $error = sprintf(self::ERROR_INVALID_NAME, $this->getEntityName(), $this->attribute->getPhpName());
        $this->error($error, self::ERROR_INVALID_NAME_CODE);

    }

    /**
     *
     */
    protected function validateType(): void
    {
        if ($this->attribute->getPhpType() !== null) {
            return;
        }
        $availableType = implode(",", self::PHP_TYPE_LIST);
        $error = sprintf(self::ERROR_NO_TYPE, $this->getEntityName(), $this->getAttributeName(), $availableType);
        $this->error($error, self::ERROR_NO_TYPE_CODE);
    }

    /**
     *
     */
    protected function validateAutoValue(): void
    {
        if (in_array($this->attribute->getAutoValue(), self::ALLOWED_AUTO_VALUE)) {
            return;
        }
        $allowed = implode(",", self::ALLOWED_AUTO_VALUE);

        $error = sprintf(self::ERROR_INVALID_AUTO_VALUE, $this->getEntityName(), $this->getAttributeName(), $this->attribute->getAutoValue(), $allowed);
        $this->error($error, self::ERROR_INVALID_AUTO_VALUE_CODE);
    }

    /**
     *
     */
    protected function validateObjectType(): void
    {

        if (!$this->attribute->getIsObject() || class_exists($this->attribute->getClassName())) {
            return;
        }
        $allowed = implode(",", self::PHP_TYPE_LIST);

        $error = sprintf(self::ERROR_INVALID_TYPE, $this->getEntityName(), $this->getAttributeName(), $this->attribute->getClassName(), $allowed);
        $this->error($error, self::ERROR_INVALID_TYPE_CODE);
    }

    /**
     *
     */
    protected function validateNonTransient(): void
    {
        if ($this->attribute->getIsTransient() || $this->attribute->getDbType() !== null) {
            return;
        }

        $error = sprintf(self::ERROR_NO_DB_TYPE, $this->getEntityName(), $this->getAttributeName());
        $this->error($error, self::ERROR_NO_DB_TYPE_CODE);
    }

    /**
     *
     */
    protected function validateWarnAutoValue(): void
    {

        if (!$this->attribute->getIsPrimaryKey() || $this->attribute->getAutoValue() !== null || $this->attribute->getIsForeignKey()) {
            return;
        }

        $warn = sprintf(self::WARN_NO_AUTO_VALUE, $this->getEntityName(), $this->getAttributeName());
        $this->logger->warn($warn, self::WARN_NO_AUTO_VALUE_CODE);
    }

}