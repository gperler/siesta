<?php

namespace Siesta\NamingStrategy;

/**
 * @author Gregor Müller
 */
interface NamingStrategy
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value) : string;

}