<?php

namespace Siesta\NamingStrategy;

class ToClassCamelCaseStrategy implements NamingStrategy
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value) : string
    {
        $func = create_function('$c', 'return strtoupper($c[1]);');
        $camelCase = preg_replace_callback('/_([a-z])/', $func, $value);
        return ucfirst($camelCase);
    }

}