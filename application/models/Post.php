<?php
namespace application\models;

use core;

/**
 * Class Post
 * @package application\models
 */
class Post extends core\model
{

    /**
     * Get all the posts as an associative array
     */
    public static function getAll()
    {
        try {

            $connection = static::getDB();

            $stmt = $connection->query("SELECT id, title, content FROM posts ORDER BY created_at");

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $results;


        } catch (\PDOException $e) {
            echo $e->getMessage();
        }



    }
}