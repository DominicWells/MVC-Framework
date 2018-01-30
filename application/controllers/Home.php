<?php
namespace application\controllers;

use \core\Controller;
use core\Router;
use \core\View;
use \application\controllers\admin;

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
        $user_ip = $_SERVER["REMOTE_ADDR"];
        if (\application\models\Users::checkIP($user_ip)) {
            return;
        } else {
            //ban IP, give error message.
            \application\models\Users::banIP($user_ip);
        }
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
        if (isset($_POST['submit'])) {
            $this->login();
        }

        $user_ip = $_SERVER["REMOTE_ADDR"];

        $attempts = \application\models\Home::checkLoginAttempts($user_ip);

        $errors = [];

        if ($attempts !== 0) {
            $remaining_attempts = 3 - $attempts;
            $errors[] = "Incorrect Credentials. Please retry! You have " . $remaining_attempts . " remaining attempt(s).";
        }
        View::renderTemplate("Home/index.html", array(
            "errors" => $errors
        ));
    }

    public function login()
    {
        $username = $_POST['user'];
        $key = $_POST['key'];

        //$key = password_hash($key,PASSWORD_BCRYPT);

        $username = htmlspecialchars($username);

        $user = \application\models\Home::checkCredentials($username,$key);

        if ($user) {

            switch ($user) {

                case "admin":
                    echo "admin";
                    break;

                case "student":
                    echo "student";
                    break;
                }
        } else {
            //the credentials are incorrect.
            $user_ip = $_SERVER["REMOTE_ADDR"];
            $current_time = date("Y-m-d h:i:sa");
            \application\models\Home::updateLoginAttempts($user_ip,$current_time);

            $attempts = \application\models\Home::checkLoginAttempts($user_ip);

            if ($attempts === 3) {
                \application\models\Users::banIP($user_ip);
            }
        }
    }

}