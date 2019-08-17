<?php

namespace Siesta\NamingStrategy;

class ToClassCamelCaseStrategy implements NamingStrategy
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function transform(string $value): string
    {
        $camelCase = preg_replace_callback('/_([a-z])/', function ($c) {
            return strtoupper($c[1]);
        }, $value);
        return ucfirst($camelCase);
    }

}