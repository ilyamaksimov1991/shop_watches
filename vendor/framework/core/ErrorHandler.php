<?php
namespace framework;

/**
 * Class ErrorHandler
 *
 * @package framework
 */
class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);

    }

    /**
     * Error handler function
     *
     * Logging error output
     *
     * @param $e
     */
    public function exceptionHandler($e)
    {
        /**
         * @var $e \Exception
         */
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayErrors('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());

    }

    /**
     * Writes errors to file
     *
     * @param string $message
     * @param string $file
     * @param string $line
     */
    private function logErrors($message = '', $file = '', $line = '')
    {
        $text_errors = "[" . date('Y-m-d H:i:s') . "]  Текст ошибки: {$message} | Файл: {$file} | Строка {$line} " . "\n===================\n";
        error_log($text_errors, 3, ROOT . '/tmp/errors.log');

    }

    /**
     * Displays an error page depending on the settings and type of development [ Production / Development ]
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param int $response error code
     */
    private function displayErrors($errno, $errstr, $errfile, $errline, $response = 404)
    {
        http_response_code($response);
        if ($response == 404 && !DEBUG) {
            require_once WWW . '/errors/404.php';
            die();
        }

        if (DEBUG) {
            require_once WWW . '/errors/dev.php';
        } else {
            require_once WWW . '/errors/prod.php';
        }
        die();
    }

}