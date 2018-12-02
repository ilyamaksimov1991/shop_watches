<?php
namespace framework;

/**
 * Class Registry
 *
 * @package framework
 */
class Registry
{
    /**
     * @see SingletonTrait
     */
    use SingletonTrait;

    /**
     * @var  array $properties
     */
    private static $properties = [];


    /**
     * Add object to storage
     *
     * @param string $key
     * @param mixed $value
     */
    public function setProperty($key, $value)
    {
        self::$properties[$key] = $value;
    }

    /**
     * Get object from storage by key
     *
     * @param string $key
     * @return mixed|null
     */
    public function getProperty($key)
    {
        if (isset(self::$properties[$key])) {
            return self::$properties[$key];
        }
        return null;

    }

    /**
     * Get all objects from the repository
     *
     * @return array
     */
    public function getProperties()
    {
        return self::$properties;
    }
}