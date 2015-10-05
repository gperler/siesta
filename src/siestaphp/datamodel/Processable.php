<?php


namespace siestaphp\datamodel;

use siestaphp\generator\GeneratorLog;

/**
 * Interface Processable
 * @package siestaphp\datamodel
 */
interface Processable
{

    /**
     * @param DataModelContainer $container
     */
    public function updateModel(DataModelContainer $container);

    /**
     * @param GeneratorLog $log
     */
    public function validate(GeneratorLog $log);

}