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
            // if login attempt fails, redirect to prevent user form resubmission
            $this->redirect('');
        }

        $attempts = \application\models\Home::checkLoginAttempts($this->user_ip);

        $errors = [];

        if ($attempts !== 0) {
            $remaining_attempts = 3 - $attempts;
            $errors[] = "Incorrect Credentials. Please retry! You have " . $remaining_attempts . " remaining attempt(s).";
        }
        View::renderTemplate("Home/index.html", array(
            "errors" => $errors
        ));
    }

    /**
     *
     * validate the user's input and handle accordingly.
     *
     * @return void
     */
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
                    $this->startSession($this->user_ip,$key,$user);
                    $this->redirect( "/admin/index");
                    break;

                case "student":
                    $this->startSession($this->user_ip,$key,$user);
                    $this->redirect("/student/index");
                    break;
                }
        } else {
            //the credentials are incorrect.
            $current_time = date("Y-m-d h:i:sa");
            \application\models\Home::updateLoginAttempts($this->user_ip,$current_time);

            $attempts = \application\models\Home::checkLoginAttempts($this->user_ip);

            if ($attempts > 3) {
                \application\models\Users::banIP($this->user_ip);

            }
        }
    }

    /**
     * Once user is validated, start a session so that he/she can continue to access the site
     *
     * @param $user_ip: the current user's IP
     * @param $key: The current user's validated key
     * @param $user: The current user privileges
     *
     * @return void
     */
    private function startSession($user_ip,$key,$user)
    {
        session_start();

        $_SESSION['IP'] = $user_ip;
        $_SESSION['user_key'] = $key;
        $_SESSION['user_type'] = $user;
    }

}