<?php
namespace core;

/**
 * Class Error and Exception Handler
 * @package core
 *
 */
class Error
{

    /**
     * converts errors to exceptions
     *
     * @param int $level Error Level
     * @param string $message Error Message
     * @param string $file Filename the error was raised in
     * @param int $line The line number of the error
     * @throws \Exception
     *
     * @return void
     */
    public static function errorHandler($level,$message,$file,$line)
    {
        if (error_reporting() !== 0) { //to keep @ operator working

            throw new \ErrorException($message,0,$level,$file,$line);
        }
    }

    /**
     * Exception Handler
     *
     * @param $exception
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        //code is 404 not found or 500 general error
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (\application\Config::SHOW_ERRORS) {
            echo "<h1>Fatal Error you douche.</h1>";
            echo "<p>Uncaught Exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>'" . $exception->getTraceAsString() . "'</pre></p>";
            echo "<p>Thrown in: '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-md-d') . '.txt';
            ini_set("error_log",$log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage(). "'";
            $message .= "\nStack Trace: '" . $exception->getTraceasString() . "'";
            $message .= "\nthrown in: '" . $exception->getFile() . " on line: " . $exception->getLine();

            error_log($message);
            //echo "<h1>An error occurred.</h1>";
            if ($code == 404) {
                echo "<h1>Page not found.</h1>";
            } else {
                echo "<h1>An error occurred.</h1>";
            }
        }
    }
}