<?php

namespace core;

/**
 * Router
 *
 *
 */

class Router
{
    /**
     *
     * Associative array of routes (routing table)
     * @var array
     *
     */
    protected $routes = array();

    /**
     *
     * Parameters for the matched route
     * @var array
     *
     */
    protected $params = array();

    /**
     *
     * Add a route to the routing table
     *
     * @param string $route the route URL
     * @param array $params Parameters (controller, action, etc.)
     *
     * @return void
     */
    public function add($route,$params = [])
    {

        //convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//','\\/',$route);

        //convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/','(?P<$1>[a-z-]+)',$route);

        //convert variables with custom regular expressions e.g. {id: \d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/','(?P<$1>\2)',$route);

        //add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     *
     * Match the route to the routes in the routing table,
     * setting the $params property if a route is found.
     *
     * @param string $url the route url
     *
     * @return boolean true if a match found, false otherwise.
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {

                //get named capture group values
                //$params = [];

                foreach ($matches as $key => $value) {

                    if (is_string($key)) {

                        $params[$key] = $value;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     *
     * Get the currently matched parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Obtain the route parameters with match function, if set.
     * Dispatch the route, creating the controller object and running the action method
     *
     * @param string $url the route url
     * @throws \Exception
     * @return void
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {

                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match("/action$/i",$action) == 0) {
                    $controller_object->$action();
                } else {
                    throw new \Exception("Method $action (in controller $controller) not found.");
                }
            } else {
                throw new \Exception("Controller class $controller not found.");
            }
        } else {
            throw new \Exception("No route matched.",404);
        }
    }

    /**
     *
     * Convert the string with hyphens to Studly Caps, e.g. post-authors => PostAuthors
     *
     * @param string $string the string to convert
     * @return string
     *
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ','',ucwords(str_replace('-',' ',$string)));
    }

    /**
     *
     * Convert the string with hyphens to camel case, e.g. add-new => addNew
     *
     * @param string $string the string to convert
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }


    /**
     * Remove the query string variables from the URL (if any). As the full query string is used for the route, any variables at the end
     * will need to be removed before the route is matched to the routing table.
     *
     * A URL of the format localhost/?page (one variable name, no value) won't work however. (N.B the .htaccess file
     * converts the first ? to a & when it's passed through to the $_SERVER variable.
     *
     * @param string $url the full url
     * @return string the URL with the query string variables removed
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&',$url,2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     *
     * @return string the request url
     */
    protected function getNamespace()
    {
        $namespace = "application\controllers\\";

        if (array_key_exists("namespace",$this->params)) {
            $namespace .= $this->params['namespace'] . "\\";
        }

        return $namespace;
    }
}