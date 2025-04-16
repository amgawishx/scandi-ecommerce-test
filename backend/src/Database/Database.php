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
    private ?PDO $dbc = null;

    public function __construct()
    {
        $this->DBHOST = getenv("DBHOST");
        $this->DBNAME = getenv("DBNAME");
        $this->DBUSER = getenv("DBUSER");
        $this->DBPASSWORD = getenv("DBPASSWORD");

        error_log("[Database] Initialized with DBHOST={$this->DBHOST}, DBNAME={$this->DBNAME}, DBUSER={$this->DBUSER}");
    }

    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$this->DBHOST};dbname={$this->DBNAME};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            error_log("[Database] Attempting connection to $dsn");
            $this->dbc = new PDO($dsn, $this->DBUSER, $this->DBPASSWORD, $options);
            error_log("[Database] Connection successful");

        } catch (PDOException $e) {
            error_log("[Database ERROR] PDOException: " . $e->getMessage());
            throw new RuntimeException("Unable to connect to the database.");
        }
    }

    public function getConnection(): PDO
    {
        if ($this->dbc === null) {
            error_log("[Database] No existing connection. Connecting...");
            $this->connect();
        } else {
            error_log("[Database] Reusing existing connection.");
        }

        return $this->dbc;
    }
}
