<?php

namespace MvpMarket\Database;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private $DBHOST;
    private $DBNAME;
    private $DBUSER;
    private $DBPASSWORD;
    private $dbc;

    public function __construct()
    {
        $this->DBHOST = getenv("DBHOST");
        $this->DBNAME = getenv("DBNAME");
        $this->DBUSER = getenv("DBUSER");
        $this->DBPASSWORD = getenv("DBPASSWORD");
    }

    private function connect()
    {
        try {
            $dsn = "mysql:host={$this->DBHOST};dbname={$this->DBNAME};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->dbc = new PDO($dsn, $this->DBUSER, $this->DBPASSWORD, $options);
        } catch (PDOException $e) {
            error_log("[DB ERROR] Connection failed: " . $e->getMessage());
            throw new RuntimeException("Unable to connect to the database.");
        }
    }

    public function getConnection(): PDO
    {
        if ($this->dbc === null) {
            $this->connect();
        }
        return $this->dbc;
    }
}
