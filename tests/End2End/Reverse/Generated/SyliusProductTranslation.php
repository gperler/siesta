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

class SyliusProductTranslation implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_translation";

    const COLUMN_ID = "id";

    const COLUMN_TRANSLATABLEID = "translatable_id";

    const COLUMN_NAME = "name";

    const COLUMN_SLUG = "slug";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_METAKEYWORDS = "meta_keywords";

    const COLUMN_METADESCRIPTION = "meta_description";

    const COLUMN_SHORTDESCRIPTION = "short_description";

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
    protected $translatableId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $metaKeywords;

    /**
     * @var string
     */
    protected $metaDescription;

    /**
     * @var string
     */
    protected $shortDescription;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var SyliusProduct
     */
    protected $105A9082C2AC5D3;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_translation_U(" : "CALL sylius_product_translation_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->translatableId) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->slug) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteString($connection, $this->metaKeywords) . ',' . Escaper::quoteString($connection, $this->metaDescription) . ',' . Escaper::quoteString($connection, $this->shortDescription) . ',' . Escaper::quoteString($connection, $this->locale) . ');';
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
        if ($cascade && $this->105A9082C2AC5D3 !== null) {
            $this->105A9082C2AC5D3->save($cascade, $cycleDetector, $connectionName);
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
        $this->translatableId = $resultSet->getIntegerValue("translatable_id");
        $this->name = $resultSet->getStringValue("name");
        $this->slug = $resultSet->getStringValue("slug");
        $this->description = $resultSet->getStringValue("description");
        $this->metaKeywords = $resultSet->getStringValue("meta_keywords");
        $this->metaDescription = $resultSet->getStringValue("meta_description");
        $this->shortDescription = $resultSet->getStringValue("short_description");
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
        $connection->execute("CALL sylius_product_translation_DB_PK($id)");
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
        $this->setTranslatableId($arrayAccessor->getIntegerValue("translatableId"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setSlug($arrayAccessor->getStringValue("slug"));
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setMetaKeywords($arrayAccessor->getStringValue("metaKeywords"));
        $this->setMetaDescription($arrayAccessor->getStringValue("metaDescription"));
        $this->setShortDescription($arrayAccessor->getStringValue("shortDescription"));
        $this->setLocale($arrayAccessor->getStringValue("locale"));
        $this->_existing = ($this->id !== null);
        $105A9082C2AC5D3Array = $arrayAccessor->getArray("105A9082C2AC5D3");
        if ($105A9082C2AC5D3Array !== null) {
            $105A9082C2AC5D3 = SyliusProductService::getInstance()->newInstance();
            $105A9082C2AC5D3->fromArray($105A9082C2AC5D3Array);
            $this->set105A9082C2AC5D3($105A9082C2AC5D3);
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
            "translatableId" => $this->getTranslatableId(),
            "name" => $this->getName(),
            "slug" => $this->getSlug(),
            "description" => $this->getDescription(),
            "metaKeywords" => $this->getMetaKeywords(),
            "metaDescription" => $this->getMetaDescription(),
            "shortDescription" => $this->getShortDescription(),
            "locale" => $this->getLocale()
        ];
        if ($this->105A9082C2AC5D3 !== null) {
            $result["105A9082C2AC5D3"] = $this->105A9082C2AC5D3->toArray($cycleDetector);
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
    public function getTranslatableId()
    {
        return $this->translatableId;
    }

    /**
     * @param int $translatableId
     * 
     * @return void
     */
    public function setTranslatableId(int $translatableId = null)
    {
        $this->translatableId = $translatableId;
    }

    /**
     * 
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * 
     * @return void
     */
    public function setName(string $name = null)
    {
        $this->name = StringUtil::trimToNull($name, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * 
     * @return void
     */
    public function setSlug(string $slug = null)
    {
        $this->slug = StringUtil::trimToNull($slug, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return void
     */
    public function setDescription(string $description = null)
    {
        $this->description = StringUtil::trimToNull($description, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     * 
     * @return void
     */
    public function setMetaKeywords(string $metaKeywords = null)
    {
        $this->metaKeywords = StringUtil::trimToNull($metaKeywords, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     * 
     * @return void
     */
    public function setMetaDescription(string $metaDescription = null)
    {
        $this->metaDescription = StringUtil::trimToNull($metaDescription, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     * 
     * @return void
     */
    public function setShortDescription(string $shortDescription = null)
    {
        $this->shortDescription = StringUtil::trimToNull($shortDescription, null);
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
     * @return SyliusProduct|null
     */
    public function get105A9082C2AC5D3(bool $forceReload = false)
    {
        if ($this->105A9082C2AC5D3 === null || $forceReload) {
            $this->105A9082C2AC5D3 = SyliusProductService::getInstance()->getEntityByPrimaryKey($this->translatableId);
        }
        return $this->105A9082C2AC5D3;
    }

    /**
     * @param SyliusProduct $entity
     * 
     * @return void
     */
    public function set105A9082C2AC5D3(SyliusProduct $entity = null)
    {
        $this->105A9082C2AC5D3 = $entity;
        $this->translatableId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductTranslation $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductTranslation $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}