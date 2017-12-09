<?php
namespace application\models;

use core;

/**
 * Class Home
 * @package application\models
 */
class Home extends core\Model
{
    /**
     * Check a User's credentials against the database and return boolean true or false if validated or not
     *
     * @param $username string
     * @param $key string
     *
     * @return boolean
     */
    public static function checkCredentials($username,$key)
    {
        try {

            $connection = static::getDB();

            $stmt = $connection->prepare("SELECT priviliges FROM users WHERE Username=:username AND Password=:key");

            $stmt->execute(array("username" => $username,"key" => $key));

            $result = $stmt->fetchColumn();

            return $result;

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}