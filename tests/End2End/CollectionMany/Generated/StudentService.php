<?php

declare(strict_types = 1);

namespace SiestaTest\End2End\CollectionMany\Generated;

use Siesta\Database\ConnectionFactory;
use Siesta\Database\Escaper;
use Siesta\Database\ResultSet;
use Siesta\Util\ArrayUtil;

class StudentService
{

    /**
     * @var StudentService
     */
    protected static $instance;

    /**
     * 
     * @return StudentService
     */
    public static function getInstance() : StudentService
    {
        if (self::$instance === null) {
            self::$instance = new StudentService();
        }
        return self::$instance;
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return Student|null
     */
    public function getEntityByPrimaryKey(int $id = null, string $connectionName = null)
    {
        if ($id === null) {
            return null;
        }
        $id = Escaper::quoteInt($id);
        $entityList = $this->executeStoredProcedure("CALL Student_SB_PK($id)", $connectionName);
        return ArrayUtil::getFromArray($entityList, 0);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteEntityByPrimaryKey(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL Student_DB_PK($id)");
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return Student[]
     */
    public function getStudentJoinStudentExam(int $id, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        return $this->executeStoredProcedure("CALL Student_S_JOIN_StudentExam_studentList($id)", $connectionName);
    }

    /**
     * @param int $id
     * @param string $connectionName
     * 
     * @return void
     */
    public function deleteStudentJoinStudentExam(int $id, string $connectionName = null)
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $id = Escaper::quoteInt($id);
        $connection->execute("CALL Student_D_JOIN_StudentExam_studentList($id)");
    }

    /**
     * 
     * @return Student
     */
    public function newInstance() : Student
    {
        return new Student();
    }

    /**
     * @param ResultSet $resultSet
     * 
     * @return Student
     */
    public function createInstanceFromResultSet(ResultSet $resultSet) : Student
    {
        $entity = $this->newInstance();
        $entity->fromResultSet($resultSet);
        return $entity;
    }

    /**
     * @param string $spCall
     * @param string $connectionName
     * 
     * @return Student[]
     */
    public function executeStoredProcedure(string $spCall, string $connectionName = null) : array
    {
        $connection = ConnectionFactory::getConnection($connectionName);
        $entityList = [];
        $resultSet = $connection->executeStoredProcedure($spCall);
        while ($resultSet->hasNext()) {
            $entityList[] = $this->createInstanceFromResultSet($resultSet);
        }
        $resultSet->close();
        return $entityList;
    }

    /**
     * @param Student[] $entityList
     * @param string $connectionName
     * 
     * @return void
     */
    public function batchSave(array $entityList, string $connectionName = null)
    {
        $batchCall = "";
        foreach ($entityList as $entity) {
            $batchCall .= $entity->createSaveStoredProcedureCall();
        }
        $connection = ConnectionFactory::getConnection($connectionName);
        $connection->execute($batchCall);
    }

}