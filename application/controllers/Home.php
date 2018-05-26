<?php
namespace application\controllers;

use \core\Controller;
use core\Misc;
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
        // essentially performs what getSessionVariables does, only does not redirect afterwards.
        session_start();

        if (isset($_SESSION['user_key'])) {
            return $_SESSION;
        } else {
            session_destroy();
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
            // if login attempt fails, redirect to prevent user form resubmission
            $this->redirect('');
        }

        $attempts = \application\models\Home::checkLoginAttempts($this->user_ip);

        $errors = [];
        $user_type = "";

        if ($attempts !== 0) {
            $remaining_attempts = 3 - $attempts;
            $errors[] = "Incorrect Credentials. Please retry! You have " . $remaining_attempts . " remaining attempt(s).";
        }

        if (isset($_SESSION['user_type'])) {
            $user_type = $_SESSION['user_type'];
        }

        View::renderTemplate("Home/index.html", array(
            "errors" => $errors,
            "user"   => $user_type
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
        $this->username = $_POST['user'];
        $key = $_POST['key'];

        //$key = password_hash($key,PASSWORD_BCRYPT);

        $this->username = htmlspecialchars($this->username);

        $user = \application\models\Home::checkCredentials($this->username,$key);

        $this->user_id = $user['user_id'];

        if ($user['priviliges']) {
            switch ($user['priviliges']) {
                case "admin":
                    $this->startSession($this->user_ip,$key,$this->user_id,$user['priviliges'],$this->username);
                    $this->redirect( "/admin/index");
                    break;

                case "student":
                    $this->startSession($this->user_ip,$key,$this->user_id,$user['priviliges'],$this->username);
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
     * storing valuable data in the started session
     *
     * @param $user_ip: the current user's IP
     * @param $key: The current user's validated key
     * @param $user_id: The current user's unique id
     * @param $user_type: The priviliges of the current user
     * @param $user: The current user privileges
     *
     * @return void
     */
    private function startSession($user_ip,$key,$user_id,$user,$username)
    {
        session_start();

        $_SESSION['IP'] = $user_ip;
        $_SESSION['user_key'] = $key;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_type'] = $user;
        $_SESSION['user_name'] = $username;
    }

}