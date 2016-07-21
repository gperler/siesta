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
use Siesta\Util\SiestaDateTime;
use Siesta\Util\StringUtil;

class SyliusProductVariantImage implements ArraySerializable
{

    const TABLE_NAME = "sylius_product_variant_image";

    const COLUMN_ID = "id";

    const COLUMN_VARIANTID = "variant_id";

    const COLUMN_PATH = "path";

    const COLUMN_CREATEDAT = "created_at";

    const COLUMN_UPDATEDAT = "updated_at";

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
    protected $variantId;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var SiestaDateTime
     */
    protected $createdAt;

    /**
     * @var SiestaDateTime
     */
    protected $updatedAt;

    /**
     * @var SyliusProductVariant
     */
    protected $C6B77D5D3B69A9AF;

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
        $spCall = ($this->_existing) ? "CALL sylius_product_variant_image_U(" : "CALL sylius_product_variant_image_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteInt($this->variantId) . ',' . Escaper::quoteString($connection, $this->path) . ',' . Escaper::quoteDateTime($this->createdAt) . ',' . Escaper::quoteDateTime($this->updatedAt) . ');';
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
        if ($cascade && $this->C6B77D5D3B69A9AF !== null) {
            $this->C6B77D5D3B69A9AF->save($cascade, $cycleDetector, $connectionName);
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
        $this->variantId = $resultSet->getIntegerValue("variant_id");
        $this->path = $resultSet->getStringValue("path");
        $this->createdAt = $resultSet->getDateTime("created_at");
        $this->updatedAt = $resultSet->getDateTime("updated_at");
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
        $connection->execute("CALL sylius_product_variant_image_DB_PK($id)");
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
        $this->setVariantId($arrayAccessor->getIntegerValue("variantId"));
        $this->setPath($arrayAccessor->getStringValue("path"));
        $this->setCreatedAt($arrayAccessor->getDateTime("createdAt"));
        $this->setUpdatedAt($arrayAccessor->getDateTime("updatedAt"));
        $this->_existing = ($this->id !== null);
        $C6B77D5D3B69A9AFArray = $arrayAccessor->getArray("C6B77D5D3B69A9AF");
        if ($C6B77D5D3B69A9AFArray !== null) {
            $C6B77D5D3B69A9AF = SyliusProductVariantService::getInstance()->newInstance();
            $C6B77D5D3B69A9AF->fromArray($C6B77D5D3B69A9AFArray);
            $this->setC6B77D5D3B69A9AF($C6B77D5D3B69A9AF);
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
            "variantId" => $this->getVariantId(),
            "path" => $this->getPath(),
            "createdAt" => ($this->getCreatedAt() !== null) ? $this->getCreatedAt()->getJSONDateTime() : null,
            "updatedAt" => ($this->getUpdatedAt() !== null) ? $this->getUpdatedAt()->getJSONDateTime() : null
        ];
        if ($this->C6B77D5D3B69A9AF !== null) {
            $result["C6B77D5D3B69A9AF"] = $this->C6B77D5D3B69A9AF->toArray($cycleDetector);
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
    public function getVariantId()
    {
        return $this->variantId;
    }

    /**
     * @param int $variantId
     * 
     * @return void
     */
    public function setVariantId(int $variantId = null)
    {
        $this->variantId = $variantId;
    }

    /**
     * 
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * 
     * @return void
     */
    public function setPath(string $path = null)
    {
        $this->path = StringUtil::trimToNull($path, 255);
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param SiestaDateTime $createdAt
     * 
     * @return void
     */
    public function setCreatedAt(SiestaDateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @return SiestaDateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param SiestaDateTime $updatedAt
     * 
     * @return void
     */
    public function setUpdatedAt(SiestaDateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param bool $forceReload
     * 
     * @return SyliusProductVariant|null
     */
    public function getC6B77D5D3B69A9AF(bool $forceReload = false)
    {
        if ($this->C6B77D5D3B69A9AF === null || $forceReload) {
            $this->C6B77D5D3B69A9AF = SyliusProductVariantService::getInstance()->getEntityByPrimaryKey($this->variantId);
        }
        return $this->C6B77D5D3B69A9AF;
    }

    /**
     * @param SyliusProductVariant $entity
     * 
     * @return void
     */
    public function setC6B77D5D3B69A9AF(SyliusProductVariant $entity = null)
    {
        $this->C6B77D5D3B69A9AF = $entity;
        $this->variantId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param SyliusProductVariantImage $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(SyliusProductVariantImage $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}