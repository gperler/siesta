<?php

namespace Siesta\NamingStrategy;

class ToCamelCaseStrategy implements NamingStrategy
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value): string
    {
        return preg_replace_callback('/_([a-z])/', function ($c) {
            return strtoupper($c[1]);
        }, $value);
    }

}