<?php

namespace Siesta\CodeGenerator;

use Siesta\Util\StringUtil;

class MethodReturnType
{

    protected $type;

    protected $nullAble;

    /**
     * MethodReturnType constructor.
     *
     * @param string|null $type
     * @param bool $nullAble
     */
    public function __construct(string $type = null, bool $nullAble = false)
    {
        $this->type = $type;
        $this->nullAble = $nullAble;
    }

    /**
     * @return string
     */
    public function generatePHPDoc()
    {
        if ($this->type === null) {
            return '@return void';
        }
        $optional = $this->nullAble ? '|null' : '';
        return '@return ' . $this->type . $optional;
    }

    /**
     * @return null|string
     */
    public function generateSignatureReturnType()
    {
        if ($this->type === null) {
            return '';
        }
        if ($this->nullAble) {
            return '';
        }
        if (StringUtil::endsWith($this->type, "[]")) {
            return ' : array';
        }
        return ' : ' . $this->type;
    }

}