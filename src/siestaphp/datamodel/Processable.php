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
     *
     * @return void
     */
    public function updateModel(DataModelContainer $container);

    /**
     * @param ValidationLogger $log
     *
     * @return void
     */
    public function validate(ValidationLogger $log);

}