<?php
namespace framework;

/**
 * Class App
 *
 * The class includes settings that should be visible throughout the application.
 *
 * @package framework
 */
class App
{
    /**
     * @var Registry
     */
    public static $app;

    public function __construct()
    {
        $query = $_SERVER['QUERY_STRING'];

        session_start();
        self::$app = Registry::instance();
        $this->getParams();
        new ErrorHandler();

        Router::dispatch($query);
    }

    /**
     *  Get all parameters visible globally
     */
    public function getParams()
    {
        $params = require_once CONFIG . '/params.php';

        if (!empty($params)) {
            foreach ($params as $key => $value) {

                self::$app->setProperty($key, $value);
            }

        }

    }

}