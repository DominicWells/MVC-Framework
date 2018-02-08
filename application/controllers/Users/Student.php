<?php

namespace application\controllers\Users;

use application\controllers\Users;

/**
 * Class Student
 * @package application\controllers\Users
 */
class Student extends \core\Controller implements Users
{

    /**
     * Before Filter - called before an action method.
     * @return void
     */
    protected function before()
    {
        // redirects user to login page if false.
        $this->redirectIfNotLoggedIn();
        // redirects user to admin index if admin logged in.
        $this->redirectIfNoPermission();
    }

    /**
     * Method that must be called for all User Inherited Controllers. Redirect user to log-in page if not logged in
     *
     * @return void
     */
    public function redirectIfNotLoggedIn()
    {
        $this->getSessionVariables();
    }

    /**
     * Method that must be called for all User Inherited Controllers. Redirect admin to admin index page
     *
     * @return void
     */
    public function redirectIfNoPermission()
    {
        if ($_SESSION['user_type'] == 'admin') {
            $this->redirect("/admin/index");
        }
    }

    /**
     * Student constructor.
     * @param array $route_params parameters from the route
     * @return void
     */
    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
    }

    /**
     * show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        echo "hello from the student's profile page";
        echo $_SESSION['user_key'];

    }
}