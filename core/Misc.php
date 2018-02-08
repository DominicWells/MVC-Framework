<?php
/**
 * Created by PhpStorm.
 * User: Dom-Wells
 * Date: 05/02/2018
 * Time: 21:11
 */

namespace core;


class Misc
{
    /**
     * Helper function to check if current user is logged in or not.
     *
     * @return bool
     */
    public static function checkUserLoggedIn()
    {

        if (isset($_SESSION['user_key'])) {
            return true;
        } else {
            return false;
        }
    }
}