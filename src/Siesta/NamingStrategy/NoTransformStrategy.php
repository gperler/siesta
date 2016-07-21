<?php

namespace Siesta\NamingStrategy;

/**
 * @author Gregor Müller
 */
class NoTransformStrategy implements NamingStrategy
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value) : string
    {
        return $value;
    }

}