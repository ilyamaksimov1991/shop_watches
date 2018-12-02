<?php
namespace framework;

/**
 * Trait SingletonTrait
 * @package framework
 */
trait SingletonTrait
{
    /**
     * @var SingletonTrait $instance
     */
    private static $instance;

    /**
     * @return SingletonTrait
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}