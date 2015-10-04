<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 21:22
 */

namespace siestaphp\driver\mysqli;


use siestaphp\datamodel\Attribute;
use siestaphp\driver\DriverFactory;
use siestaphp\driver\ResultSet;

/**
 * Class ColumnMetaData
 * @package siestaphp\driver\mysqli
 */
class ColumnMetaData {

    const KEY_PRIMARY = "PRI";
    const KEY_UNIQUE = "UNI";

    const KEY_NAME_PRIMARY = "PRIMARY";

    protected $tableName;
    protected $alterStatements;
    protected $field;
    protected $type;
    protected $nullAllowed;
    protected $key;
    protected $extra;
    protected $used;
    protected $unique_index;
    protected $unique_indexType;


    /**
     * @param ResultSet $resultSet
     * @param $tableName
     */
    public function __construct(ResultSet $resultSet, $tableName) {
        $this->alterStatements = array();
        $this->tableName = $tableName;
        $this->columnName = $resultSet->getStringValue('Field');
        $this->columnType = strtoupper($resultSet->getStringValue('Type'));
        $this->nullAllowed = $resultSet->getStringValue('Null') === 'YES';
        $this->key = $resultSet->getStringValue('Key');
        $this->extra = $resultSet->getStringValue('Extra');
        $this->used = false;
        $this->unique_index = null;
        $this->unique_indexType = null;
    }

    /**
     * stores additional index information for this column
     * @param ResultSet $resultSet
     */
    public function addIndexInformation(ResultSet $resultSet) {

        // do not store the primary key index her.
        $keyName = $resultSet->getStringValue("Key_name");
        if ($keyName === self::KEY_NAME_PRIMARY) {
            return;
        }

        // store keyname and indextype (BTREE / HASH)
        $this->unique_index = $keyName;
        $this->unique_indexType =  $resultSet->getStringValue("Index_type");

    }


    public function getColumnName() {
        return $this->columnName;
    }

    public function checkAttribute(Attribute $attribute) {
        // check if we have the attribute
        if ($this->columnName !== $attribute->getDBName()) {
            return;
        }

        // this is used
        $this->used = true;

        // compare
        $this->compare($attribute);

    }


    /**
     * @param Attribute $attribute
     */
    private function compare(Attribute $attribute) {
        // check if db type has changed
        if ($this->columnType !== $attribute->getDatabaseType()) {
            $this->alterStatements[] = $this->createTypeModifier($attribute);
        }

    }

    /**
     * @param Attribute $attribute
     */
    private function compareUniqueIndex(Attribute $attribute) {

        // if attribute is defined as unique, but no unique index in column data create one
//        if($attribute->isUnique() && $this->unique_index === null) {
//            return;
//        }
    }


    /**
     * @param Attribute $attribute
     * @return string
     */
    private function createTypeModifier(Attribute $attribute) {
        return $this->createAlterStatementBegin("MODIFY") . MySQLDriver::quote($this->columnName) . " " . $attribute->getDBType();
    }

    /**
     * @param Attribute $attribute
     * @return string
     */
    private function createAddUniqueIndex(Attribute $attribute) {
        // get name of unique index
        $uniqueIndexName = MySQLDriver::quote(TableCreator::getUniqueIndexName($attribute->getDBName()));

        $columnName = MySQLDriver::quote($attribute->getDBName());

        return $this->createAlterStatementBegin("ADD UNIQUE INDEX ") . $uniqueIndexName  . " (" . $columnName . ")";
    }

    /**
     * @param Attribute $attribute
     */
    private function dropUniqueIndex(Attribute $attribute) {

    }

    /**
     * @param $modifier
     * @return string
     */
    private function createAlterStatementBegin($modifier) {
        return "ALTER TABLE " . MySQLDriver::quote($this->tableName) . " " . $modifier . " ";
    }

    // ADD UNIQUE INDEX `NAME_UNIQUE` (`NAME` ASC);

    // ALTER TABLE `siestajs`.`ARTIST` DROP INDEX `NAME_UNIQUE` ;



    /*
     *
     * ALTER TABLE `siestajs`.`ARTIST`
ADD CONSTRAINT `FK_LABEL`
  FOREIGN KEY (`ID`)
  REFERENCES `siestajs`.`Label` (`ID`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;
     *
     */
}