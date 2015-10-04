<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 28.06.15
 * Time: 18:55
 */

namespace siestaphp\datamodel;
use siestaphp\generator\GeneratorLog;


/**
 * Interface Processable
 * @package siestaphp\datamodel
 */
interface Processable {

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container);


    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log);

}