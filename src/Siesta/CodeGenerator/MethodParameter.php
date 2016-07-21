<?php

namespace Siesta\CodeGenerator;

use Siesta\Util\StringUtil;

/**
 * @author Gregor Müller
 */
class MethodParameter
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $defaultValue;

    /**
     * MethodParameter constructor.
     *
     * @param string $type
     * @param string $name
     * @param string $defaultValue
     */
    public function __construct(string $type, string $name, string $defaultValue = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getPHPDocLine()
    {
        return '@param ' . $this->type . ' $' . $this->name;
    }

    /**
     * @return string
     */
    public function getSignaturePart()
    {
        $type = StringUtil::endsWith($this->type, "[]") ? 'array' : $this->type;
        $optional = $this->defaultValue ? ' = ' .  $this->defaultValue  : '';
        return $type . ' $' . $this->name . $optional;
    }

}