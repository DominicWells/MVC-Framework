<?php

namespace core;

/**
 * Class Base Controller
 * @package core
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Class constructor.
     * @param array $route_params Parameters from the route
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $method = $name . 'Action';

        if (method_exists($this,$method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this,$method],$arguments);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller. " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before()
    {

    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after()
    {

    }

    /**
     * @param $location: Query String.
     *
     * @return void
     */
    protected function redirect($location)
    {
        header("Location: http://" . $_SERVER["HTTP_HOST"] . $location);
        exit;
    }

}