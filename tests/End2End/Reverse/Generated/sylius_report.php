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

class sylius_report implements ArraySerializable
{

    const TABLE_NAME = "sylius_report";

    const COLUMN_ID = "id";

    const COLUMN_NAME = "name";

    const COLUMN_DESCRIPTION = "description";

    const COLUMN_CODE = "code";

    const COLUMN_RENDERER = "renderer";

    const COLUMN_RENDERER_CONFIGURATION = "renderer_configuration";

    const COLUMN_DATA_FETCHER = "data_fetcher";

    const COLUMN_DATA_FETCHER_CONFIGURATION = "data_fetcher_configuration";

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
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $renderer_configuration;

    /**
     * @var string
     */
    protected $data_fetcher;

    /**
     * @var string
     */
    protected $data_fetcher_configuration;

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
        $spCall = ($this->_existing) ? "CALL sylius_report_U(" : "CALL sylius_report_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteInt($this->id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->description) . ',' . Escaper::quoteString($connection, $this->code) . ',' . Escaper::quoteString($connection, $this->renderer) . ',' . Escaper::quoteString($connection, $this->renderer_configuration) . ',' . Escaper::quoteString($connection, $this->data_fetcher) . ',' . Escaper::quoteString($connection, $this->data_fetcher_configuration) . ');';
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
        $this->description = $resultSet->getStringValue("description");
        $this->code = $resultSet->getStringValue("code");
        $this->renderer = $resultSet->getStringValue("renderer");
        $this->renderer_configuration = $resultSet->getStringValue("renderer_configuration");
        $this->data_fetcher = $resultSet->getStringValue("data_fetcher");
        $this->data_fetcher_configuration = $resultSet->getStringValue("data_fetcher_configuration");
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
        $connection->execute("CALL sylius_report_DB_PK($id)");
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
        $this->setDescription($arrayAccessor->getStringValue("description"));
        $this->setCode($arrayAccessor->getStringValue("code"));
        $this->setRenderer($arrayAccessor->getStringValue("renderer"));
        $this->setRenderer_configuration($arrayAccessor->getStringValue("renderer_configuration"));
        $this->setData_fetcher($arrayAccessor->getStringValue("data_fetcher"));
        $this->setData_fetcher_configuration($arrayAccessor->getStringValue("data_fetcher_configuration"));
        $this->_existing = ($this->id !== null);
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
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "code" => $this->getCode(),
            "renderer" => $this->getRenderer(),
            "renderer_configuration" => $this->getRenderer_configuration(),
            "data_fetcher" => $this->getData_fetcher(),
            "data_fetcher_configuration" => $this->getData_fetcher_configuration()
        ];
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
        $this->name = StringUtil::trimToNull($name, 255);
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * 
     * @return void
     */
    public function setCode(string $code = null)
    {
        $this->code = StringUtil::trimToNull($code, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string $renderer
     * 
     * @return void
     */
    public function setRenderer(string $renderer = null)
    {
        $this->renderer = StringUtil::trimToNull($renderer, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getRenderer_configuration()
    {
        return $this->renderer_configuration;
    }

    /**
     * @param string $renderer_configuration
     * 
     * @return void
     */
    public function setRenderer_configuration(string $renderer_configuration = null)
    {
        $this->renderer_configuration = StringUtil::trimToNull($renderer_configuration, null);
    }

    /**
     * 
     * @return string|null
     */
    public function getData_fetcher()
    {
        return $this->data_fetcher;
    }

    /**
     * @param string $data_fetcher
     * 
     * @return void
     */
    public function setData_fetcher(string $data_fetcher = null)
    {
        $this->data_fetcher = StringUtil::trimToNull($data_fetcher, 255);
    }

    /**
     * 
     * @return string|null
     */
    public function getData_fetcher_configuration()
    {
        return $this->data_fetcher_configuration;
    }

    /**
     * @param string $data_fetcher_configuration
     * 
     * @return void
     */
    public function setData_fetcher_configuration(string $data_fetcher_configuration = null)
    {
        $this->data_fetcher_configuration = StringUtil::trimToNull($data_fetcher_configuration, null);
    }

    /**
     * @param sylius_report $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(sylius_report $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}