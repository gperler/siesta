<?php

namespace Siesta\NamingStrategy;

class ToCamelCaseStrategy implements NamingStrategy
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value) : string
    {
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $value);
    }

}