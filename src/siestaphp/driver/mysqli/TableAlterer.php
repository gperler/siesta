<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 21.06.15
 * Time: 18:40
 */

namespace siestaphp\driver\mysqli;


use siestaphp\builder\entity\Entity;
use siestaphp\driver\DriverFactory;
use siestaphp\driver\TableDoesNotExistException;

class TableAlterer
{

    const MYSQL_QUOTE = "`";

    protected $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }


    public function checkTables()
    {
        $this->checkMainTable();
    }

    private function checkMainTable()
    {
        // all are marked as not needed

        $tableMetaData = new TableMetaData();
        $tableMetaData->initialize($this->entity->getTableName());


    }


    /**
     * @param {string} $value
     * @return string
     */
    private function mySQLQuote($value)
    {
        return self::MYSQL_QUOTE . $value . self::MYSQL_QUOTE;
    }
}