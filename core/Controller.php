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
     * The current user's IP Address
     * @var string
     */
    protected $user_ip;

    /**
     * Class constructor.
     * @param array $route_params Parameters from the route
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
        $this->user_ip = $_SERVER['REMOTE_ADDR'];
        $this->checkAccess();
        $this->logout();
    }

    /**
     * Check current IP is valid before creating a new controller instance. If not, serve up an error template and prevent from accessing the site.
     *
     * @return void
     */
    protected function checkAccess()
    {
        if (\application\models\Users::checkIP($this->user_ip) === false) {

            View::renderTemplate("Misc/index.html");
            exit();
        }
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

    /**
     * Logs out the currently logged in user.
     *
     * @return void
     */
    protected function logout()
    {
        if (isset($_POST['logout'])) {
            $this->redirect("");
        }
    }

    /**
     * Returns Session Variables if user is logged in, otherwise redirects to login page.
     *
     * @return mixed
     */
    protected function getSessionVariables()
    {
        session_start();

        if (isset($_SESSION['user_key'])) {
            return $_SESSION;
        } else {
            session_destroy();
            $this->redirect("");
        }
    }
}