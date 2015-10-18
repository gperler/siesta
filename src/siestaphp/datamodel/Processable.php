<?php


namespace siestaphp\datamodel;

use siestaphp\generator\ValidationLogger;

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
     * @param ValidationLogger $log
     */
    public function validate(ValidationLogger $log);

}