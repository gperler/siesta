<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;
use Siesta\Util\StringUtil;

class sylius_product_option_translation implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_option_translation";

    const COLUMN_ID = "id";

    const COLUMN_TRANSLATABLE_ID = "translatable_id";

    const COLUMN_PRESENTATION = "presentation";

    const COLUMN_LOCALE = "locale";

    /**
     * @var bool
     */
    protected $_existing;

    /**
     * @var array
     */
    protected $_rawJSON;

    /**
     * @var array
     */
    protected $_rawSQLResult;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $translatable_id;

    /**
     * @var string
     */
    protected $presentation;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var sylius_product_option
     */
    protected $CBA491AD2C2AC5D3;

    /**
     * 
     */
    public function __construct()
    {
        $this->_existing = false;
    }

    /**
     * @param string $connectionName
     * 
     * @return string
     */
    public function createSaveStoredProcedureCall(string $connectionName = null) : string
    {
        $spCall = ($this->_existing) ? "CALL sylius_product_option_translation_U(" : "CALL sylius_product_option_translation_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->translatable_id) . ',' . Escaper::quoteString($connection, $this->presentation) . ',' . Escaper::quoteString($connection, $this->locale) . ');';
    }

    /**
     * @param bool $cascade
     * @param CycleDetector $cycleDetector
     * @param string $connectionName
     * 
     * @return void
     */
    public function save(bool $cascade = false, CycleDetector $cycleDetector = null, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return;
        }
        if ($cascade && $this->CBA491AD2C2AC5D3 !== null) {
            $this->CBA491AD2C2AC5D3->save($cascade, $cycleDetector, $connectionName);
        }
        $call = $this->createSaveStoredProcedureCall($connectionName);
        $connection->execute($call);
        $this->_existing = true;
        if (!$cascade) {
            return;
        }
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return void
     */
    public function fromResultSet(ResultSet $resultSet)
    {
        $this->_existing = true;
        $this->_rawSQLResult = $resultSet->getNext();
        $this->id = $resultSet->getIntegerValue("id");
        $this->translatable_id = $resultSet->getIntegerValue("translatable_id");
        $this->presentation = $resultSet->getStringValue("presentation");
        $this->locale = $resultSet->getStringValue("locale");
    }

    /**
     * @param string $key
     * 
     * @return string|null
     */
    public function getAdditionalColumn(string $key)
    {
        return ArrayUtil::getFromArray($this->_rawSQLResult, $key);
    }

    /**
     * @param string $connectionName
     * 
     * @return void
     */
    public function delete(string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($this->id);
        $connection->execute("CALL sylius_product_option_translation_DB_PK($id)");
        $this->_existing = false;
    }

    /**
     * @param array $data
     * 
     * @return void
     */
    public function fromArray(array $data)
    {
        $this->_rawJSON = $data;
        $arrayAccessor = new ArrayAccessor($data);
        $this->setId($arrayAccessor->getIntegerValue("id"));
        $this->setTranslatable_id($arrayAccessor->getIntegerValue("translatable_id"));
        $this->setPresentation($arrayAccessor->getStringValue("presentation"));
        $this->setLocale($arrayAccessor->getStringValue("locale"));
        $this->_existing = ($this->id !== null);
        $CBA491AD2C2AC5D3Array = $arrayAccessor->getArray("CBA491AD2C2AC5D3");
        if ($CBA491AD2C2AC5D3Array !== null) {
            $CBA491AD2C2AC5D3 = sylius_product_optionService::getInstance()->newInstance();
            $CBA491AD2C2AC5D3->fromArray($CBA491AD2C2AC5D3Array);
            $this->setCBA491AD2C2AC5D3($CBA491AD2C2AC5D3);
        }
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return array|null
     */
    public function toArray(CycleDetector $cycleDetector = null)
    {
        if ($cycleDetector === null) {
            $cycleDetector = new DefaultCycleDetector();
        }
        if (!$cycleDetector->canProceed(self::TABLE_NAME, $this)) {
            return null;
        }
        $result = [
            "id" => $this->getId(),
            "translatable_id" => $this->getTranslatable_id(),
            "presentation" => $this->getPresentation(),
            "locale" => $this->getLocale()
        ];
        if ($this->CBA491AD2C2AC5D3 !== null) {
            $result["CBA491AD2C2AC5D3"] = $this->CBA491AD2C2AC5D3->toArray($cycleDetector);
        }
        return $result;
    }

    /**
     * @param string $jsonString
     * 
     * @return void
     */
    public function fromJSON(string $jsonString)
    {
        $this->fromArray(json_decode($jsonString, true));
    }

    /**
     * @param CycleDetector $cycleDetector
     * 
     * @return string
     */
    public function toJSON(CycleDetector $cycleDetector = null) : string
    {
        return json_encode($this->toArray($cycleDetector));
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param int $id
     * 
     * @return void
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return int|null
     */
    public function getTranslatable_id()
    {
        return $this->translatable_id;
    }

    /**
     * @param int $translatable_id
     * 
     * @return void
     */
    public function setTranslatable_id(int $translatable_id = null)
    {
        $this->translatable_id = $translatable_id;
    }

    /**
     * 
     * @return string|null
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param string $presentation
     * 
     * @return void
     */
    public function setPresentation(string $presentation = null)
    {
        $this->presentation = StringUtil::trimToNull($presentation, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * 
     * @return void
     */
    public function setLocale(string $locale = null)
    {
        $this->locale = StringUtil::trimToNull($locale, 255);
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_option|null
     */
    public function getCBA491AD2C2AC5D3(bool $forceReload = false)
    {
        if ($this->CBA491AD2C2AC5D3 === null || $forceReload) {
            $this->CBA491AD2C2AC5D3 = sylius_product_optionService::getInstance()->getEntityByPrimaryKey($this->translatable_id);
        }
        return $this->CBA491AD2C2AC5D3;
    }

    /**
     * @param sylius_product_option $entity
     * 
     * @return void
     */
    public function setCBA491AD2C2AC5D3(sylius_product_option $entity = null)
    {
        $this->CBA491AD2C2AC5D3 = $entity;
        $this->translatable_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_option_translation $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_option_translation $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}