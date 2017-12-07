<?php

/**
 *
 * Front Controller
 *
 */

/**
 * Twig
 */
require("../vendor/autoload.php");
Twig_Autoloader::register();

/**
 *
 * Routing
 */

/**
 * Autoloader
 */
spl_autoload_register(function($class) {
    $root = dirname(__DIR__); //get the parent directory
    $file = $root . '/' . str_replace('\\','/',$class) . '.php';
    if (is_readable($file)) {
        require($root . '/' . str_replace('\\','/',$class) . '.php');
    }
});

/**
 * Error and Exception Handling
 */
error_reporting(E_ALL);
set_error_handler("core\Error::errorHandler");
set_exception_handler("core\Error::exceptionHandler");

$router = new core\Router();

//add our routes
$router->add('',array('controller' => 'Home', 'action' => 'index' ));
$router->add('posts', array('controller' => 'Posts', 'action' => 'index'));
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}',['namespace' => 'admin']);

$router->dispatch($_SERVER["QUERY_STRING"]);
