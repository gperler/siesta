<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reverse\Generated;

use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;

class sylius_product_archetype_option implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_archetype_option";

    const COLUMN_PRODUCT_ARCHETYPE_ID = "product_archetype_id";

    const COLUMN_OPTION_ID = "option_id";

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
    protected $product_archetype_id;

    /**
     * @var int
     */
    protected $option_id;

    /**
     * @var sylius_product_option
     */
    protected $BCE763A7A7C41D6F;

    /**
     * @var sylius_product_archetype
     */
    protected $BCE763A7FE884EAC;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_archetype_option_U(" : "CALL sylius_product_archetype_option_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getProduct_archetype_id(true, $connectionName);
        $this->getOption_id(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->product_archetype_id) . ',' . Escaper::quoteInt($this->option_id) . ');';
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
        if ($cascade && $this->BCE763A7A7C41D6F !== null) {
            $this->BCE763A7A7C41D6F->save($cascade, $cycleDetector, $connectionName);
        }
        if ($cascade && $this->BCE763A7FE884EAC !== null) {
            $this->BCE763A7FE884EAC->save($cascade, $cycleDetector, $connectionName);
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
        $this->product_archetype_id = $resultSet->getIntegerValue("product_archetype_id");
        $this->option_id = $resultSet->getIntegerValue("option_id");
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
        $product_archetype_id = Escaper::quoteInt($this->product_archetype_id);
        $option_id = Escaper::quoteInt($this->option_id);
        $connection->execute("CALL sylius_product_archetype_option_DB_PK($product_archetype_id,$option_id)");
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
        $this->setProduct_archetype_id($arrayAccessor->getIntegerValue("product_archetype_id"));
        $this->setOption_id($arrayAccessor->getIntegerValue("option_id"));
        $this->_existing = ($this->product_archetype_id !== null) && ($this->option_id !== null);
        $BCE763A7A7C41D6FArray = $arrayAccessor->getArray("BCE763A7A7C41D6F");
        if ($BCE763A7A7C41D6FArray !== null) {
            $BCE763A7A7C41D6F = sylius_product_optionService::getInstance()->newInstance();
            $BCE763A7A7C41D6F->fromArray($BCE763A7A7C41D6FArray);
            $this->setBCE763A7A7C41D6F($BCE763A7A7C41D6F);
        }
        $BCE763A7FE884EACArray = $arrayAccessor->getArray("BCE763A7FE884EAC");
        if ($BCE763A7FE884EACArray !== null) {
            $BCE763A7FE884EAC = sylius_product_archetypeService::getInstance()->newInstance();
            $BCE763A7FE884EAC->fromArray($BCE763A7FE884EACArray);
            $this->setBCE763A7FE884EAC($BCE763A7FE884EAC);
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
            "product_archetype_id" => $this->getProduct_archetype_id(),
            "option_id" => $this->getOption_id()
        ];
        if ($this->BCE763A7A7C41D6F !== null) {
            $result["BCE763A7A7C41D6F"] = $this->BCE763A7A7C41D6F->toArray($cycleDetector);
        }
        if ($this->BCE763A7FE884EAC !== null) {
            $result["BCE763A7FE884EAC"] = $this->BCE763A7FE884EAC->toArray($cycleDetector);
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
    public function getProduct_archetype_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->product_archetype_id === null) {
            $this->product_archetype_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->product_archetype_id;
    }

    /**
     * @param int $product_archetype_id
     * 
     * @return void
     */
    public function setProduct_archetype_id(int $product_archetype_id = null)
    {
        $this->product_archetype_id = $product_archetype_id;
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return int|null
     */
    public function getOption_id(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->option_id === null) {
            $this->option_id = SequencerFactory::nextSequence("", self::TABLE_NAME, $connectionName);
        }
        return $this->option_id;
    }

    /**
     * @param int $option_id
     * 
     * @return void
     */
    public function setOption_id(int $option_id = null)
    {
        $this->option_id = $option_id;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_option|null
     */
    public function getBCE763A7A7C41D6F(bool $forceReload = false)
    {
        if ($this->BCE763A7A7C41D6F === null || $forceReload) {
            $this->BCE763A7A7C41D6F = sylius_product_optionService::getInstance()->getEntityByPrimaryKey($this->option_id);
        }
        return $this->BCE763A7A7C41D6F;
    }

    /**
     * @param sylius_product_option $entity
     * 
     * @return void
     */
    public function setBCE763A7A7C41D6F(sylius_product_option $entity = null)
    {
        $this->BCE763A7A7C41D6F = $entity;
        $this->option_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param bool $forceReload
     * 
     * @return sylius_product_archetype|null
     */
    public function getBCE763A7FE884EAC(bool $forceReload = false)
    {
        if ($this->BCE763A7FE884EAC === null || $forceReload) {
            $this->BCE763A7FE884EAC = sylius_product_archetypeService::getInstance()->getEntityByPrimaryKey($this->product_archetype_id);
        }
        return $this->BCE763A7FE884EAC;
    }

    /**
     * @param sylius_product_archetype $entity
     * 
     * @return void
     */
    public function setBCE763A7FE884EAC(sylius_product_archetype $entity = null)
    {
        $this->BCE763A7FE884EAC = $entity;
        $this->product_archetype_id = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param sylius_product_archetype_option $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_product_archetype_option $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getProduct_archetype_id() === $entity->getProduct_archetype_id() && $this->getOption_id() === $entity->getOption_id();
    }

}