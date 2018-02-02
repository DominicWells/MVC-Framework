<?php

namespace application\controllers\admin;

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