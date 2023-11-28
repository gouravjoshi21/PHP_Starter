<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    public $connection;
    public $statement;

    public function __construct () {
        try {
            $config = $GLOBALS['configObj'];

            $dsn = 'mysql:'.http_build_query([
                'host' => $config->get('HOSTNAME') ?? 'localhost',
                'port' => $config->get('DB_PORT') ?? '3306',
                'dbname' => $config->get('DB_NAME'),
                'charset' => 'utf8mb4'
            ], '', ';');
    
            $this->connection = new PDO($dsn, $config->get('USERNAME'), $config->get('PASSWORD'), [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            return $this->connection;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public function query ($query, $params = []) {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get () {
        return $this->statement->fetchAll();
    }

    public function find () {
        return $this->statement->fetch();
    }

    public function findOrFail () {
        $result = $this->find();

        if (!$result) abort();

        return $result;
    }
}