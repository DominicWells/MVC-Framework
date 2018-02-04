<?php

namespace application\controllers\Users;

use application\controllers\Users;
/**
 * Class Admin
 * @package application\controllers\admin
 */
class Admin extends \core\Controller implements Users
{
    /**
     * Before Filter - called before an action method.
     * @return void
     */
    protected function before()
    {
        // redirects user to login page if false.
        $this->redirectIfNotLoggedIn();
        // redirects user to student index if student logged in.
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
     * Method that must be called for all User Inherited Controllers. Redirect student to student index page
     *
     * @return void
     */
    public function redirectIfNoPermission()
    {
        if ($_SESSION['user_type'] == 'student') {
            $this->redirect("/student/index");
        }
    }

    /**
     * Admin constructor.
     * @param array $route_params parameters from the route
     * @return void
     */
    public function __construct(array $route_params)
    {
        parent::__construct($route_params);
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction() {
        echo "User admin index";
    }
}