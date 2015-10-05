<?php


namespace siestaphp\datamodel\attribute;

use siestaphp\datamodel\DatabaseColumn;
use siestaphp\datamodel\DataModelContainer;
use siestaphp\datamodel\Processable;
use siestaphp\generator\GeneratorLog;
use siestaphp\naming\XMLAttribute;

/**
 * Class Attribute represents th attribute of an entity
 * @package siestaphp\datamodel
 */
class Attribute implements Processable, AttributeSource, AttributeTransformerSource, AttributeDatabaseSource, DatabaseColumn
{
    private static $ALLOWED_PHP_TYPES = array("bool", "int", "float", "string", "DateTime");

    private static $ALLOWED_AUTO_VALUE = array("", "autoincrement", "uuid");

    const PARAMETER_PREFIX = "P_";

    const AUTO_VALUE_UUID = "uuid";

    const AUTO_VALUE_AUTOINCREMENT = "autoincrement";

    /**
     * @var AttributeSource
     */
    protected $attributeSource;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $phpType;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var string
     */
    protected $databaseType;

    /**
     * @var bool
     */
    protected $primaryKey;

    /**
     * @var string
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $autoValue;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @param AttributeSource $source
     */
    public function setSource(AttributeSource $source)
    {
        $this->attributeSource = $source;

        $this->storeAttributeData();

    }

    private function storeAttributeData()
    {
        $this->name = $this->attributeSource->getName();
        $this->phpType = $this->attributeSource->getPHPType();
        $this->databaseName = $this->attributeSource->getDatabaseName();
        $this->databaseType = $this->attributeSource->getDatabaseType();
        $this->length = 0;
        $this->primaryKey = $this->attributeSource->isPrimaryKey();
        $this->defaultValue = $this->attributeSource->getDefaultValue();
        $this->autoValue = $this->attributeSource->getAutoValue();
        $this->required = $this->attributeSource->isRequired();

        if (preg_match("/VARCHAR\((.*?)\)/i", $this->databaseType, $regResult)) {
            $this->length = (int)$regResult [1];
        }

    }

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container)
    {
        if (!$this->databaseName) {
            $this->databaseName = $this->name;
        }

        if ($this->autoValue === self::AUTO_VALUE_UUID) {
            $this->phpType = 'string';
            $this->databaseType = "VARCHAR(36)";
            $this->length = 36;
        }
    }

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log)
    {
        $log->errorIfAttributeNotSet($this->name, XMLAttribute::ATTRIBUTE_NAME, XMLAttribute::ELEMENT_ATTRIBUTE_NAME);

        $log->errorIfAttributeNotSet($this->databaseType, XMLAttribute::ATTRIBUTE_DATABASE_TYPE, XMLAttribute::ELEMENT_ATTRIBUTE_NAME);

        $log->errorIfNotInList($this->phpType, self::$ALLOWED_PHP_TYPES, XMLAttribute::ATTRIBUTE_TYPE, XMLAttribute::ELEMENT_ATTRIBUTE_NAME);

        $log->errorIfNotInList($this->autoValue, self::$ALLOWED_AUTO_VALUE, XMLAttribute::ATTRIBUTE_AUTO_VALUE, XMLAttribute::ELEMENT_ATTRIBUTE_NAME);

        if ($this->isPrimaryKey() and !$this->autoValue) {
            $log->warn("Primary key '" . $this->name . "' does not have an autovalue attribute.");
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPHPType()
    {
        return $this->phpType;
    }

    /**
     * @return string
     */
    public function getAutoValue()
    {
        return $this->autoValue;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->databaseType;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return bool
     */
    public function isPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return bool;
     */
    public function isDateTime()
    {
        return $this->phpType === 'DateTime';
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    public function getSQLParameterName()
    {
        return self::PARAMETER_PREFIX . $this->databaseName;
    }

}