<?php
declare(strict_types=1);

namespace Siesta\Util;

/**
 * @author Gregor Müller
 */
class ArrayAccessor
{

    /**
     * @var array
     */
    protected array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function get(string $key): mixed
    {
        if (!isset ($this->data [$key])) {
            return null;
        }
        return $this->data[$key];
    }

    /**
     * @param $key
     *
     * @return bool|null
     */
    public function getBooleanValue($key): ?bool
    {
        $value = $this->get($key);
        if ($value === null) {
            return null;
        }
        return ($value === true or $value === 1);
    }

    /**
     * @param string $key
     *
     * @return int|null
     */
    public function getIntegerValue($key): ?int
    {
        $value = $this->get($key);
        if ($value === null) {
            return null;
        }
        return (integer)$value;
    }

    /**
     * @param string $key
     *
     * @return float|null
     */
    public function getFloatValue($key): ?float
    {
        $value = $this->get($key);
        if ($value === null) {
            return null;
        }
        return (float)$value;
    }

    /**
     * @param string $key
     * @param int|null $maxlength
     *
     * @return string|null
     */
    public function getStringValue($key, int $maxlength = null): ?string
    {
        $value = $this->get($key);
        if ($value === null) {
            return null;
        }
        return ($maxlength === null) ? $value : mb_substr($value, 0, $maxlength);
    }

    /**
     * @param string $key
     *
     * @return null|SiestaDateTime
     */
    public function getDateTime($key): ?SiestaDateTime
    {
        $value = $this->get($key);
        if ($value === null) {
            return null;
        }
        return new SiestaDateTime($value);
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    public function getArray($key): ?array
    {
        $value = $this->get($key);
        if ($value === null or !is_array($value)) {
            return null;
        }
        return $value;
    }
}