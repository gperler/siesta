<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Collection\Generated;

use SiestaTest\End2End\Collection\Entity\CartItem;
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

class CartEntity implements ArraySerializable
{

    const TABLE_NAME = "Cart";

    const COLUMN_ID = "id";

    const COLUMN_NAME = "name";

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
     * @var string
     */
    protected $name;

    /**
     * @var CartItem[]
     */
    protected $cartItem;

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
        $spCall = ($this->_existing) ? "CALL Cart_U(" : "CALL Cart_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->name) . ');';
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
        $call = $this->createSaveStoredProcedureCall($connectionName);
        $connection->execute($call);
        $this->_existing = true;
        if (!$cascade) {
            return;
        }
        if ($this->cartItem !== null) {
            foreach ($this->cartItem as $entity) {
                $entity->save($cascade, $cycleDetector, $connectionName);
            }
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
        $this->name = $resultSet->getStringValue("name");
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
        $connection->execute("CALL Cart_DB_PK($id)");
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
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->_existing = ($this->id !== null);
        $cartItemArray = $arrayAccessor->getArray("cartItem");
        if ($cartItemArray !== null) {
            foreach ($cartItemArray as $entityArray) {
                $cartItem = CartItemEntityService::getInstance()->newInstance();
                $cartItem->fromArray($entityArray);
                $this->addToCartItem($cartItem);
            }
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
            "name" => $this->getName()
        ];
        $result["cartItem"] = [];
        if ($this->cartItem !== null) {
            foreach ($this->cartItem as $entity) {
                $result["cartItem"][] = $entity->toArray($cycleDetector);
            }
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
        $this->name = StringUtil::trimToNull($name, 100);
    }

    /**
     * @param bool $forceReload
     * @param string $connectionName
     * 
     * @return CartItem[]
     */
    public function getCartItem(bool $forceReload = false, string $connectionName = null) : array
    {
        if ($this->cartItem === null || $forceReload) {
            $this->cartItem = CartItemEntityService::getInstance()->getEntityByCartReference($this->getId(true, $connectionName), $connectionName);
        }
        return $this->cartItem;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteFromCartItem(int $id = null, string $connectionName = null)
    {
        CartItemEntityService::getInstance()->deleteEntityByCartReference($this->getId(true, $connectionName), $id, $connectionName);
        if (($this->cartItem === null) OR ($id === null)) {
            $this->cartItem = null;
            return;
        }
        foreach ($this->cartItem as $index => $entity) {
            if ($id === $entity->getId()) {
                array_splice($this->cartItem, $index, 1);
                return;
            }
        }
    }

    /**
     * @param CartItem $entity
     * 
     * @return void
     */
    public function addToCartItem(CartItem $entity)
    {
        $entity->setCart($this);
        if ($this->cartItem === null) {
            $this->cartItem = [];
        }
        $this->cartItem[] = $entity;
    }

    /**
     * @param CartEntity $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(CartEntity $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}