<?php
/**
 * Created by PhpStorm.
 * User: Dom-Wells
 * Date: 29/10/2017
 * Time: 12:04
 */

namespace application\controllers;


use \core\Controller;
use \core\View;

class Home extends Controller
{
    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {

    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {

    }

    /**
     *
     * show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        /*View::render("Home/index.php",array(
            'name' => 'Dominic',
            'colours' => array(
                'red',
                'green',
                'blue'
            )
        ));*/
        View::renderTemplate("Home/index.html",array(
            'name' => 'Dominic',
            'colours' => array(
            'red',
            'green',
            'blue'
            )
        ));
    }
}