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
     * Check a Users's credentials against the database and return boolean true or false if validated or not
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

            $stmt = $connection->prepare("SELECT user_id,priviliges FROM users WHERE Username=:username AND Password=:key");

            $stmt->execute(array("username" => $username,"key" => $key));

            $result = $stmt->fetch();

            return $result;

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Checks to see if user still has remaining login attempts to use. Returns false if not, integer attempt number if still valid.
     *
     * @param $user_ip
     * @return mixed
     */
    public static function checkLoginAttempts($user_ip) {

        try {

            $connection = static::getDB();

            //first, check if ip already exists in database.
            $stmt = $connection->prepare("SELECT Attempts FROM LoginAttempts WHERE IP=:user_ip");
            $stmt->execute(array("user_ip" => $user_ip));

            $result = $stmt->fetchColumn();

            //Return number of attempts used.
             if (empty($result)) {

                 $result = 0;
                 return $result;
            } else {
                 return $result;
            }

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * After a failed login attempt, updates the attempt number against the user's ip.
     *
     * @param $user_ip string the Users's Ip Address
     * @param $current_time string current time of failed login attempt
     * @return mixed
     */
    public static function updateLoginAttempts($user_ip,$current_time)
    {
        try {

            $connection = static::getDB();

            //first, check if ip already exists in database.
            $stmt = $connection->prepare("SELECT Attempts FROM LoginAttempts WHERE IP=:user_ip");
            $stmt->execute(array(
                "user_ip" => $user_ip
            ));

            $result = $stmt->fetchColumn();



            if ($result) {
                //we need an update statement.
                $result = $result + 1;
                $stmt = $connection->prepare("UPDATE LoginAttempts SET Attempts=:attempts WHERE IP=:user_ip");
                $stmt->execute(array(
                    "attempts" => $result,
                    "user_ip" => $user_ip
                ));

            } else {
                //we need an insert statement.
                $attempt_number = 1;
                $stmt = $connection->prepare("INSERT INTO LoginAttempts(IP,Attempts,LastLogin) VALUES (:user_ip,:attempt_number,:current_time)");
                $stmt->execute(array(
                    "user_ip" => $user_ip,
                    "attempt_number" => $attempt_number,
                    "current_time" => $current_time
                ));
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}