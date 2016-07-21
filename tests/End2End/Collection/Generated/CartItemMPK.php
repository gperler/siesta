<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Collection\Generated;

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

class CartItemMPK implements ArraySerializable
{

    const TABLE_NAME = "CartItemMPK";

    const COLUMN_ID1 = "id1";

    const COLUMN_ID2 = "id2";

    const COLUMN_NAME = "name";

    const COLUMN_CARTID1 = "fk_cart_1";

    const COLUMN_CARTID2 = "fk_cart_2";

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
     * @var string
     */
    protected $id1;

    /**
     * @var string
     */
    protected $id2;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $cartId1;

    /**
     * @var string
     */
    protected $cartId2;

    /**
     * @var CartMPK
     */
    protected $cart;

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
        $spCall = ($this->_existing) ? "CALL CartItemMPK_U(" : "CALL CartItemMPK_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId1(true, $connectionName);
        $this->getId2(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->id1) . ',' . Escaper::quoteString($connection, $this->id2) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->cartId1) . ',' . Escaper::quoteString($connection, $this->cartId2) . ');';
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
        if ($cascade && $this->cart !== null) {
            $this->cart->save($cascade, $cycleDetector, $connectionName);
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
        $this->id1 = $resultSet->getStringValue("id1");
        $this->id2 = $resultSet->getStringValue("id2");
        $this->name = $resultSet->getStringValue("name");
        $this->cartId1 = $resultSet->getStringValue("fk_cart_1");
        $this->cartId2 = $resultSet->getStringValue("fk_cart_2");
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
        $id1 = Escaper::quoteString($connection, $this->id1);
        $id2 = Escaper::quoteString($connection, $this->id2);
        $connection->execute("CALL CartItemMPK_DB_PK($id1,$id2)");
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
        $this->setId1($arrayAccessor->getStringValue("id1"));
        $this->setId2($arrayAccessor->getStringValue("id2"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setCartId1($arrayAccessor->getStringValue("cartId1"));
        $this->setCartId2($arrayAccessor->getStringValue("cartId2"));
        $this->_existing = ($this->id1 !== null) && ($this->id2 !== null);
        $cartArray = $arrayAccessor->getArray("cart");
        if ($cartArray !== null) {
            $cart = CartMPKService::getInstance()->newInstance();
            $cart->fromArray($cartArray);
            $this->setCart($cart);
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
            "id1" => $this->getId1(),
            "id2" => $this->getId2(),
            "name" => $this->getName(),
            "cartId1" => $this->getCartId1(),
            "cartId2" => $this->getCartId2()
        ];
        if ($this->cart !== null) {
            $result["cart"] = $this->cart->toArray($cycleDetector);
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
     * @return string|null
     */
    public function getId1(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id1 === null) {
            $this->id1 = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id1;
    }

    /**
     * @param string $id1
     * 
     * @return void
     */
    public function setId1(string $id1 = null)
    {
        $this->id1 = StringUtil::trimToNull($id1, 36);
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return string|null
     */
    public function getId2(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id2 === null) {
            $this->id2 = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id2;
    }

    /**
     * @param string $id2
     * 
     * @return void
     */
    public function setId2(string $id2 = null)
    {
        $this->id2 = StringUtil::trimToNull($id2, 36);
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
     * 
     * @return string|null
     */
    public function getCartId1()
    {
        return $this->cartId1;
    }

    /**
     * @param string $cartId1
     * 
     * @return void
     */
    public function setCartId1(string $cartId1 = null)
    {
        $this->cartId1 = StringUtil::trimToNull($cartId1, 36);
    }

    /**
     * 
     * @return string|null
     */
    public function getCartId2()
    {
        return $this->cartId2;
    }

    /**
     * @param string $cartId2
     * 
     * @return void
     */
    public function setCartId2(string $cartId2 = null)
    {
        $this->cartId2 = StringUtil::trimToNull($cartId2, 36);
    }

    /**
     * @param bool $forceReload
     * 
     * @return CartMPK|null
     */
    public function getCart(bool $forceReload = false)
    {
        if ($this->cart === null || $forceReload) {
            $this->cart = CartMPKService::getInstance()->getEntityByPrimaryKey($this->cartId1, $this->cartId2);
        }
        return $this->cart;
    }

    /**
     * @param CartMPK $entity
     * 
     * @return void
     */
    public function setCart(CartMPK $entity = null)
    {
        $this->cart = $entity;
        $this->cartId1 = ($entity !== null) ? $entity->getId1(true) : null;
        $this->cartId2 = ($entity !== null) ? $entity->getId2(true) : null;
    }

    /**
     * @param CartItemMPK $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(CartItemMPK $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId1() === $entity->getId1() && $this->getId2() === $entity->getId2();
    }

}