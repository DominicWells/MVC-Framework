<?php

/**
 *
 * Front Controller
 *
 */

//echo "Requested URL = " . $_SERVER['QUERY_STRING'];

//require the controller class
//require("../application/controllers/Home/Posts.php");

/**
 * Twig
 */
require("../vendor/autoload.php");
Twig_Autoloader::register();

/**
 *
 * Routing
 */

//require("../core/Router.php");

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
//$router->add('posts/new', array('controller' => 'Posts', 'action' => 'new'));
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}',['namespace' => 'admin']);


//display routing table
//echo "<pre>";
//print_r($router->getRoutes());
//echo htmlspecialchars(print_r($router->getRoutes(), true));
//echo "</pre>";//

//match the requested route
//$url = $_SERVER['QUERY_STRING'];
//
//if ($router->match($url)) {
//
//    echo "<pre>";
//    print_r($router->getParams());
//    echo "</pre>";
//} else {
//    echo "No route found for that URL :(";
//}
$router->dispatch($_SERVER["QUERY_STRING"]);
