<?php

namespace application\controllers\Admin;

/**
 * Class Admin
 * @package application\controllers\admin
 */
class Admin extends \core\Controller
{
    /**
     * Before Filter
     * @return void
     */
    protected function before()
    {
        //$this->sessionInit();
        if (session_status() == PHP_SESSION_NONE) {
            echo "no session";
        } else {
            echo "is a session";
        }
        //ensure admin is logged in, for example.
        //return false;
    }

    /**
     * Show the index page
     * @return void
     */
    public function indexAction() {
        echo "User admin index";
    }
}