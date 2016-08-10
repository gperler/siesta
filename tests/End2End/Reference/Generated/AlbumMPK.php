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

class AlbumMPK implements ArraySerializable
{

    const TABLE_NAME = "AlbumMPK";

    const COLUMN_ID_1 = "id_1";

    const COLUMN_ID_2 = "id_2";

    const COLUMN_NAME = "name";

    const COLUMN_ARTISTID_1 = "fk_artist_1";

    const COLUMN_ARTISTID_2 = "fk_artist_2";

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
    protected $id_1;

    /**
     * @var string
     */
    protected $id_2;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $artistId_1;

    /**
     * @var string
     */
    protected $artistId_2;

    /**
     * @var ArtistMPK
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
        $spCall = ($this->_existing) ? "CALL AlbumMPK_U(" : "CALL AlbumMPK_I(";
        $connection = ConnectionFactory::getConnection($connectionName);
        $this->getId_1(true, $connectionName);
        $this->getId_2(true, $connectionName);
        return $spCall . Escaper::quoteString($connection, $this->id_1) . ',' . Escaper::quoteString($connection, $this->id_2) . ',' . Escaper::quoteString($connection, $this->name) . ',' . Escaper::quoteString($connection, $this->artistId_1) . ',' . Escaper::quoteString($connection, $this->artistId_2) . ');';
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
        $this->id_1 = $resultSet->getStringValue("id_1");
        $this->id_2 = $resultSet->getStringValue("id_2");
        $this->name = $resultSet->getStringValue("name");
        $this->artistId_1 = $resultSet->getStringValue("fk_artist_1");
        $this->artistId_2 = $resultSet->getStringValue("fk_artist_2");
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
        $id_1 = Escaper::quoteString($connection, $this->id_1);
        $id_2 = Escaper::quoteString($connection, $this->id_2);
        $connection->execute("CALL AlbumMPK_DB_PK($id_1,$id_2)");
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
        $this->setId_1($arrayAccessor->getStringValue("id_1"));
        $this->setId_2($arrayAccessor->getStringValue("id_2"));
        $this->setName($arrayAccessor->getStringValue("name"));
        $this->setArtistId_1($arrayAccessor->getStringValue("artistId_1"));
        $this->setArtistId_2($arrayAccessor->getStringValue("artistId_2"));
        $this->_existing = ($this->id_1 !== null) && ($this->id_2 !== null);
        $artistArray = $arrayAccessor->getArray("artist");
        if ($artistArray !== null) {
            $artist = ArtistMPKService::getInstance()->newInstance();
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
            "id_1" => $this->getId_1(),
            "id_2" => $this->getId_2(),
            "name" => $this->getName(),
            "artistId_1" => $this->getArtistId_1(),
            "artistId_2" => $this->getArtistId_2()
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
    public function getId_1(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id_1 === null) {
            $this->id_1 = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id_1;
    }

    /**
     * @param string $id_1
     * 
     * @return void
     */
    public function setId_1(string $id_1 = null)
    {
        $this->id_1 = StringUtil::trimToNull($id_1, 36);
    }

    /**
     * @param bool $generateKey
     * @param string $connectionName
     * 
     * @return string|null
     */
    public function getId_2(bool $generateKey = false, string $connectionName = null)
    {
        if ($generateKey && $this->id_2 === null) {
            $this->id_2 = SequencerFactory::nextSequence("uuid", self::TABLE_NAME, $connectionName);
        }
        return $this->id_2;
    }

    /**
     * @param string $id_2
     * 
     * @return void
     */
    public function setId_2(string $id_2 = null)
    {
        $this->id_2 = StringUtil::trimToNull($id_2, 36);
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
    public function getArtistId_1()
    {
        return $this->artistId_1;
    }

    /**
     * @param string $artistId_1
     * 
     * @return void
     */
    public function setArtistId_1(string $artistId_1 = null)
    {
        $this->artistId_1 = StringUtil::trimToNull($artistId_1, 36);
    }

    /**
     * 
     * @return string|null
     */
    public function getArtistId_2()
    {
        return $this->artistId_2;
    }

    /**
     * @param string $artistId_2
     * 
     * @return void
     */
    public function setArtistId_2(string $artistId_2 = null)
    {
        $this->artistId_2 = StringUtil::trimToNull($artistId_2, 36);
    }

    /**
     * @param bool $forceReload
     * 
     * @return ArtistMPK|null
     */
    public function getArtist(bool $forceReload = false)
    {
        if ($this->artist === null || $forceReload) {
            $this->artist = ArtistMPKService::getInstance()->getEntityByPrimaryKey($this->artistId_1, $this->artistId_2);
        }
        return $this->artist;
    }

    /**
     * @param ArtistMPK $entity
     * 
     * @return void
     */
    public function setArtist(ArtistMPK $entity = null)
    {
        $this->artist = $entity;
        $this->artistId_1 = ($entity !== null) ? $entity->getId_1(true) : null;
        $this->artistId_2 = ($entity !== null) ? $entity->getId_2(true) : null;
    }

    /**
     * @param AlbumMPK $entity
     * 
     * @return bool
     */
    public function arePrimaryKeyIdentical(AlbumMPK $entity = null) : bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->getId_1() === $entity->getId_1() && $this->getId_2() === $entity->getId_2();
    }

}