<?php

namespace Siesta\NamingStrategy;

/**
 * @author Gregor Müller
 */
class ToUnderScoreStrategy implements NamingStrategy
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value) : string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $value)), '_');
    }


    public function getColumnName() {

    }
}