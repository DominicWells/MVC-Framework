<?php
/**
 * Created by PhpStorm.
 * User: Dom-Wells
 * Date: 23/11/2017
 * Time: 19:26
 */

namespace core;


class View
{
    /**
     * Render a view file
     *
     * @param string $view the view file
     *
     * @return void
     * @throws \Exception
     */
    public static function render($view,$args = array())
    {
        extract($args,EXTR_SKIP);

        $file = "../application/views/$view"; //relative to core directory

        if (is_readable($file)) {
            require($file);
        } else {
            throw new \Exception("$file not found.");
        }
    }

    /**
     * Render a view using Twig
     *
     * @param string $template the Template file
     * @param array $args Associative array of data to display in the view (optional)
     */
    public static function renderTemplate($template,$args = array())
    {
        // append logged in as a default parameter for the array.
        $logged_in = Misc::checkUserLoggedIn();

        if ($logged_in) {
            $logged_in = true;
        } else {
            $logged_in = false;
        }

        $args["logged_in"] = $logged_in;
        array_push($args,$logged_in);

        static $twig = NULL;

        if ($twig === NULL) {
            $loader = new \Twig_Loader_Filesystem("../application/views");
            $twig = new \Twig_Environment($loader);
        }

        echo $twig->render($template,$args);
    }
}