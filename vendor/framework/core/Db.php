<?php

namespace framework;

/**
 * Class Db
 *
 * Class singleton database connection
 * @package framework
 */
class Db
{
    /**
     * @see SingletonTrait
     */
    use SingletonTrait;

    private function __construct()
    {
        $db = require_once CONFIG . '/db.php';
        require_once ROOT . '/vendor/redBinPhp/rb-mysql.php';

        \R::setup($db['dsn'], $db['user'], $db['pass']);

        if (!\R::testConnection()) {
            throw new \Exception("Нет соединения с бд", 500);

        }

        \R::freeze(TRUE);
        if (DEBUG) {
            \R::debug(true, 1);
        }

        \R::ext('xdispense', function ($type) {
            return \R::getRedBean()->dispense($type);
        });
    }

}