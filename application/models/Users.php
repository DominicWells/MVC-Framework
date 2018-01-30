<?php
namespace application\models;

use core;

/**
 * Class Users
 * @package application\models
 */
class Users extends core\model
{
    public static function banIP($user_ip)
    {
        try {

            $current_time = date("Y-m-d h:i:sa");

            $connection = static::getDB();

            $stmt = $connection->prepare("INSERT INTO bannedips (IP) VALUE (:IP)");

            $stmt->execute(array(
                "IP" => $user_ip
            ));

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function checkIP($user_ip)
    {
        try {
            $connection = static::getDB();

            $stmt = $connection->prepare("SELECT * FROM bannedips WHERE IP =:IP");

            $stmt->execute(array(
                "IP" => $user_ip
            ));

            $result = $stmt->fetchColumn();

            if (empty($result)) {
                return true;
            } else {
                return false;
            }

        } catch (\PDOException $e ) {
            echo $e->getMessage();
        }
    }
}