<?php
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
        //check if session already exists.
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
        //if user is not logged in, we need to get them to do so ASAP so they can use the site.
        View::renderTemplate("Home/index.html");

        if (isset($_POST['submit'])) {

            $username = $_POST['user'];
            $key = $_POST['key'];

            //$key = password_hash($key,PASSWORD_BCRYPT);

            $username = htmlspecialchars($username);

            $user = \application\models\Home::checkCredentials($username,$key);

            if ($user) {

                switch ($user) {

                    case "admin":
                        echo "admin";

                }

            } else {
                //the credentials are incorrect - show an error message to the user.

            }
        }

    }
}