<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\Reference\Generated;

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

class AlbumUUID implements ArraySerializable
{

    const TABLE_NAME = "AlbumUUID";

    const COLUMN_ID = "id";

    const COLUMN_NAME = "name";

    const COLUMN_ARTISTID = "fk_artist";

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
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $artistId;

    /**
     * @var ArtistUUID
     */
    protected $artist;

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
        $spCall = ($this->_existing) ? "CALL AlbumUUID_U(" : "CALL AlbumUUID_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->id) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->artistId) . ');';
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
        if ($cascade && $this->artist !== null) {
            $this->artist->save($cascade, $cycleDetector, $connectionName);
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
        $this->id = $resultSet->getStringValue("id");
        $this->name = $resultSet->getStringValue("name");
        $this->artistId = $resultSet->getStringValue("fk_artist");
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
        $id = Escaper::quoteString($connection, $this->id);
        $connection->execute("CALL AlbumUUID_DB_PK($id)");
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
        $this->setId($arrayAccessor->getStringValue("id"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setArtistId($arrayAccessor->getStringValue("artistId"));
        $this->_existing = ($this->id !== null);
        $artistArray = $arrayAccessor->getArray("artist");
        if ($artistArray !== null) {
            $artist = ArtistUUIDService::getInstance()->newInstance();
            $artist->fromArray($artistArray);
            $this->setArtist($artist);
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
            "name" => $this->getName(),
            "artistId" => $this->getArtistId()
        ];
        if ($this->artist !== null) {
            $result["artist"] = $this->artist->toArray($cycleDetector);
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
    public function getId(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id === null) {
            $this->id = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id;
    }

    /**
     * @param string $id
     * 
     * @return void
     */
    public function setId(string $id = null)
    {
        $this->id = StringUtil::trimToNull($id, 36);
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
    public function getArtistId()
    {
        return $this->artistId;
    }

    /**
     * @param string $artistId
     * 
     * @return void
     */
    public function setArtistId(string $artistId = null)
    {
        $this->artistId = StringUtil::trimToNull($artistId, 36);
    }

    /**
     * @param bool $forceReload
     * 
     * @return ArtistUUID|null
     */
    public function getArtist(bool $forceReload = false)
    {
        if ($this->artist === null || $forceReload) {
            $this->artist = ArtistUUIDService::getInstance()->getEntityByPrimaryKey($this->artistId);
        }
        return $this->artist;
    }

    /**
     * @param ArtistUUID $entity
     * 
     * @return void
     */
    public function setArtist(ArtistUUID $entity = null)
    {
        $this->artist = $entity;
        $this->artistId = ($entity !== null) ? $entity->getId(true) : null;
    }

    /**
     * @param AlbumUUID $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(AlbumUUID $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId() === $entity->getId();
    }

}