<?php
declare(strict_types=1);
namespace Siesta\Exception;

use Siesta\Util\ArrayUtil;
use Siesta\Util\ObjectUtil;

/**
 * @author Gregor Müller
 */
class XMLNotValidException extends \Exception implements SiestaException
{

    const ERROR_MESSAGE = "%s Code : %s Level : %s Column: %s";
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
    public function getErrorList() : array
    {
        $errorMessageList = [];
        foreach ($this->errorList as $error) {
            $message = ObjectUtil::getFromObject($error, "message");
            $code = ObjectUtil::getFromObject($error, "code");
            $level = ObjectUtil::getFromObject($error, "level");
            $column = ObjectUtil::getFromObject($error, "column");

            $errorMessageList[] = sprintf(self::ERROR_MESSAGE, $message, $code, $level, $column);
        }
        return $errorMessageList;
    }

    /**
     * @return string
     */
    public function getFileName() : string
    {
        $firstError = ArrayUtil::getFromArray($this->errorList, 0);
        if ($firstError === null) {
            return "not defined";
        }
        return ObjectUtil::getFromObject($firstError, "file");

    }

}

