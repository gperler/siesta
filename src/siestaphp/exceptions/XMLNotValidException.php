<?php

namespace siestaphp\exceptions;

use siestaphp\util\Util;

/**
 * Class XMLNotValidException
 * @package siestaphp\exceptions
 */
class XMLNotValidException extends \Exception
{

    /**
     * @var array
     */
    protected $errorList;

    /**
     * @param $errorList
     */
    public function __construct(array $errorList)
    {
        $this->errorList = $errorList;
    }

    /**
     * @return string[]
     */
    public function getErrorList()
    {
        $errorMessageList = array();
        foreach ($this->errorList as $error) {
            $errorMessageList[] = trim(Util::getFromObject($error, "message")) . sprintf(" Code : %s Level : %s Column: %s", Util::getFromObject($error, "code"), Util::getFromObject($error, "level"), Util::getFromObject($error, "column"));
        }
        return $errorMessageList;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        $firstError = Util::getFromArray($this->errorList, 0);
        if (!$firstError) {
            return "noting";
        }
        return Util::getFromObject($firstError, "file");

    }

}

