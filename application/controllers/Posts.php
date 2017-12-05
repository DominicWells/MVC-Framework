<?php
/**
 * Created by PhpStorm.
 * User: Dom-Wells
 * Date: 26/10/2017
 * Time: 22:51
 */

namespace application\controllers;
use application\models\Post;
use core\Controller;
use core\View;


class Posts extends Controller
{
    /**
     *
     * show the index page.
     *
     * @return void
     */
    public function indexAction()
    {
        $posts = Post::getAll();

        View::renderTemplate("Posts/index.html",array(
            'posts' => $posts
        ));
    }

    /**
     *
     * show the add new page.
     *
     * @return void
     */
    public function addNewAction()
    {
        echo "Hello from the addNew action in the Posts controller!";
    }

    /**
     * show the edit page
     * @return void
     */
    public function editAction()
    {
        echo "Hello from the edit action in the Posts Controller!";
        echo "<p>Route Parameters: <pre>" . htmlspecialchars(print_r($this->route_params,true)) . "</pre></p>";
    }
}