<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 21:58
 */

namespace siestaphp\driver\mysqli;


use siestaphp\driver\DriverFactory;
use siestaphp\driver\TableDoesNotExistException;

class TableMetaData {

    protected $driver;

    protected $tableName;

    /**
     * @var ColumnMetaData[]
     */
    protected $columnList;

    public function initialize($tableName) {

        $this->driver = DriverFactory::getDriver();

        $this->tableName = $tableName;
        $this->columnList = array();

        $this->extractColumns();

        $this->extractIndexes();

    }

    private function extractColumns() {

/* SELECT
        *
        FROM
	information_schema.COLUMNS
WHERE
	TABLE_SCHEMA = 'siestajs' AND
    TABLE_NAME = 'ARTIST'; */

        $sql = "SHOW COLUMNS FROM " . MySQLDriver::quote($this->tableName);

        $driver = DriverFactory::getDriver();

        try {
            $resulSet = $driver->query($sql);

            while ($resulSet->hasNext()) {
                $cmd = new ColumnMetaData($resulSet, $this->tableName);
                $this->columnList[$cmd->getFieldName()] = $cmd;
            }

        } catch (TableDoesNotExistException $e) {
        }

    }

    private function extractIndexes() {
        $sql = "SHOW INDEX FROM " . MySQLDriver::quote($this->tableName);

        $driver = DriverFactory::getDriver();

        try {
            $resulSet = $driver->query($sql);

            while ($resulSet->hasNext()) {
                $columnName = $resulSet->getStringValue('Column_name');

                $this->columnList[$columnName]->addIndexInformation($resulSet->getNext());
            }

        } catch (TableDoesNotExistException $e) {
        }
    }

    private function extractConstraints() {

    }

}