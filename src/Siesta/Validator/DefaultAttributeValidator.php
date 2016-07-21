<?php
declare(strict_types = 1);

namespace Siesta\Validator;

use Siesta\Contract\AttributeValidator;
use Siesta\Model\Attribute;
use Siesta\Model\DataModel;
use Siesta\Model\Entity;
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

    const ERROR_INVALID_AUTOVALUE = "Entity '%s' Attribute '%s' has invalid autovalue '%s'. Available values %s";

    const ERROR_INVALID_AUTOVALUE_CODE = 1203;

    const ERROR_NO_DB_TYPE = "Entity '%s' Attribute '%s' is not transient and has no dbType.";

    const ERROR_NO_DB_TYPE_CODE = 1204;

    const WARN_NO_AUTOVALUE = "Entity '%s' Attribute '%s' is primary key but does not have an autovalue.";

    const WARN_NO_AUTOVALUE_CODE = 1205;

    const AUTO_VALUE_UUID = "uuid";

    const AUTO_VALUE_AUTOINCREMENT = "autoincrement";

    const ALLOWED_AUTO_VALUE = [
        null,
        self::AUTO_VALUE_AUTOINCREMENT,
        self::AUTO_VALUE_UUID
    ];

    const PHP_TYPE_LIST = [
        "bool",
        "int",
        "float",
        "string",
        "SiestaDateTime",
        "array",
        "json"
    ];

    /**
     * @var DataModel
     */
    protected $datamodel;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Attribute
     */
    protected $attribute;

    /**
     * @var ValidationLogger
     */
    protected $logger;

    /**
     * @param DataModel $dataModel
     * @param Entity $entity
     * @param Attribute $attribute
     * @param ValidationLogger $logger
     */
    public function validate(DataModel $dataModel, Entity $entity, Attribute $attribute, ValidationLogger $logger)
    {
        $this->logger = $logger;
        $this->datamodel = $dataModel;
        $this->entity = $entity;
        $this->attribute = $attribute;

        $this->validateName();
        $this->validateType();
        $this->validateAutovalue();
        $this->validateObjectType();
        $this->validateNonTransient();
        $this->validateWarnAutovalue();
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
    protected function getAttributeName()
    {
        return $this->attribute->getPhpName();
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
    protected function validateName()
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
    protected function validateType()
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
    protected function validateAutovalue()
    {
        if (in_array($this->attribute->getAutoValue(), self::ALLOWED_AUTO_VALUE)) {
            return;
        }
        $allowed = implode(",", self::ALLOWED_AUTO_VALUE);

        $error = sprintf(self::ERROR_INVALID_AUTOVALUE, $this->getEntityName(), $this->getAttributeName(), $this->attribute->getAutoValue(), $allowed);
        $this->error($error, self::ERROR_INVALID_AUTOVALUE_CODE);
    }

    /**
     *
     */
    protected function validateObjectType()
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
    protected function validateNonTransient()
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
    protected function validateWarnAutovalue()
    {

        if (!$this->attribute->getIsPrimaryKey() || $this->attribute->getAutoValue() !== null) {
            return;
        }

        $warn = sprintf(self::WARN_NO_AUTOVALUE, $this->getEntityName(), $this->getAttributeName());
        $this->logger->warn($warn, self::WARN_NO_AUTOVALUE_CODE);
    }

}