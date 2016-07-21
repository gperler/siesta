<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CustomServiceClass\Generated;

use SiestaTest\End2End\CustomServiceClass\ServiceClass\ServiceFactory;
use Siesta\Contract\ArraySerializable;
use Siesta\Contract\CycleDetector;
use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Sequencer\SequencerFactory;
use Siesta\Util\ArrayAccessor;
use Siesta\Util\ArrayUtil;
use Siesta\Util\DefaultCycleDetector;

class ServiceReference implements ArraySerializable
{

    const TABLE_NAME = "service_reference";

    const COLUMN_ID2 = "id2";

    const COLUMN_SERVICEFACTORYID = "serviceFactoryId";

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
    protected $id2;

    /**
     * @var int
     */
    protected $serviceFactoryId;

    /**
     * @var ServiceFactoryEntity
     */
    protected $serviceFactory;

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
        $spCall = ($this->_existing) ? "CALL service_reference_U(" : "CALL service_reference_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId2(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id2) . ',' . Escaper::quoteInt($this->serviceFactoryId) . ');';
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
        if ($cascade && $this->serviceFactory !== null) {
            $this->serviceFactory->save($cascade, $cycleDetector, $connectionName);
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
        $this->id2 = $resultSet->getIntegerValue("id2");
        $this->serviceFactoryId = $resultSet->getIntegerValue("serviceFactoryId");
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
        $id2 = Escaper::quoteInt($this->id2);
        $connection->execute("CALL service_reference_DB_PK($id2)");
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
        $this->setId2($arrayAccessor->getIntegerValue("id2"));
        $this->setServiceFactoryId($arrayAccessor->getIntegerValue("serviceFactoryId"));
        $this->_existing = ($this->id2 !== null);
        $serviceFactoryArray = $arrayAccessor->getArray("serviceFactory");
        if ($serviceFactoryArray !== null) {
            $serviceFactory = ServiceFactory::getInstance()->newInstance();
            $serviceFactory->fromArray($serviceFactoryArray);
            $this->setServiceFactory($serviceFactory);
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
            "id2" => $this->getId2(),
            "serviceFactoryId" => $this->getServiceFactoryId()
        ];
        if ($this->serviceFactory !== null) {
            $result["serviceFactory"] = $this->serviceFactory->toArray($cycleDetector);
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
    public function getId2(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id2 === null) {
            $this->id2 = SequencerFactory::nextSequence("autoincrement", self::TABLE_NAME, $connectionName);
        }
        return $this->id2;
    }

    /**
     * @param int $id2
     * 
     * @return void
     */
    public function setId2(int $id2 = null)
    {
        $this->id2 = $id2;
    }

    /**
     * 
     * @return int|null
     */
    public function getServiceFactoryId()
    {
        return $this->serviceFactoryId;
    }

    /**
     * @param int $serviceFactoryId
     * 
     * @return void
     */
    public function setServiceFactoryId(int $serviceFactoryId = null)
    {
        $this->serviceFactoryId = $serviceFactoryId;
    }

    /**
     * @param bool $forceReload
     * 
     * @return ServiceFactoryEntity|null
     */
    public function getServiceFactory(bool $forceReload = false)
    {
        if ($this->serviceFactory === null || $forceReload) {
            $this->serviceFactory = ServiceFactory::getInstance()->getEntityByPrimaryKey($this->serviceFactoryId);
        }
        return $this->serviceFactory;
    }

    /**
     * @param ServiceFactoryEntity $entity
     * 
     * @return void
     */
    public function setServiceFactory(ServiceFactoryEntity $entity = null)
    {
        $this->serviceFactory = $entity;
        $this->serviceFactoryId = ($entity !== null) ? $entity->getId1(true) : null;
    }

    /**
     * @param ServiceReference $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(ServiceReference $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId2() === $entity->getId2();
    }

}