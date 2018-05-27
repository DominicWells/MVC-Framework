<?php

namespace application\controllers\Users;

use application\controllers\Users;
use core\View;
/**
 * Class Admin
 * @package application\controllers\admin
 */
class Admin extends \core\Controller implements Users
{
    /**
     * Error property to pass into view if the file submitted by the user is of an incorrect format
     * @var string
     */
    private $filetype_error;

    /**
     * Property to inform view when the user has successfully uploaded a photo to their profile.
     * @var
     */
    private $upload_success;

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
    public function indexAction()
    {

        // store current user username in variable to display in view
        $this->username = $_SESSION['user_name'];

        if (isset($_POST['upload_profile_image'])) {
            $this->uploadImage();
        }

        View::renderTemplate("admin/index.html", array(
            "username" => $this->username,
            "filetype_error" => $this->filetype_error,
            "upload_success" => $this->upload_success
        ));
    }

    /**
     * Uploads An Image.
     *
     * @return void
     */
    protected function uploadImage()
    {

        if ($_FILES) {

            $accepted_extensions = array('jpeg','png','gif','jpg');

            $path = $_FILES['image']['name'];
            $ext = pathinfo($path,PATHINFO_EXTENSION);

            if (!in_array($ext,$accepted_extensions)) {

                // The User tried to upload an invalid file type.
                $this->filetype_error = "You cannot upload invalid file types!!";
            } else {
                // upload approved! Create a name for the image
                $name = $_SESSION['user_type'] . "-" . $_SESSION['user_id'];

                $image = $_FILES['image']['tmp_name'];
                $new_path = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $name . "." . $ext;

                move_uploaded_file($image, $new_path);
                $this->upload_success = "Congratulations! You have successfully uploaded a profile picture.";
            }
        }
    }

    /**
     * Loads the User's Uploaded profile image (if it exists)
     * and renders it. Otherwise, returns placeholder image
     *
     * @return
     */
    protected function loadImage()
    {

    }
}