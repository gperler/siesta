<?php

namespace siestaphp\runtime;

use siestaphp\driver\Driver;
use siestaphp\driver\DriverFactory;
use siestaphp\runtime\impl\HttpRequestImpl;
use siestaphp\runtime\impl\SequenceGeneratorImpl;
use siestaphp\runtime\impl\UUIDGeneratorImpl;

/**
 * Class ServiceLocator
 * @package siestaphp\runtime
 */
class ServiceLocator
{

    private static $instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @return HttpRequest
     */
    public static function getHttpRequest()
    {
        return self::getInstance()->getHttpRequestImpl();
    }

    /**
     * @return UUIDGenerator
     */
    public static function getUUIDGenerator()
    {
        return self::getInstance()->getUUIDGeneratorImpl();
    }

    /**
     * @var HttpRequest
     */
    protected $httpRequest;

    /**
     * @var UUIDGenerator
     */
    protected $uuidGenerator;

    /**
     *
     */
    public function __construct()
    {
        $this->initialize();
    }

    protected function initialize()
    {
        $this->httpRequest = new HttpRequestImpl();
        $this->uuidGenerator = new UUIDGeneratorImpl();

    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequestImpl()
    {
        return $this->httpRequest;
    }

    /**
     * @return UUIDGenerator
     */
    public function getUUIDGeneratorImpl()
    {
        return $this->uuidGenerator;
    }

}