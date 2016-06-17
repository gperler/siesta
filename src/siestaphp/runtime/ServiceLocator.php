<?php

namespace siestaphp\runtime;

use siestaphp\runtime\impl\HttpRequestImpl;
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
     * @return UUIDGenerator
     */
    public static function getUUIDGenerator()
    {
        return self::getInstance()->getUUIDGeneratorImpl();
    }

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
        $this->uuidGenerator = new UUIDGeneratorImpl();

    }

    /**
     * @return UUIDGenerator
     */
    public function getUUIDGeneratorImpl()
    {
        return $this->uuidGenerator;
    }

}