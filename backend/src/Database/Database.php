<?php

namespace MvpMarket\Database;

use PDO;

class Database
{
    private $DBHOST = getenv("DBHOST");
    private $DBNAME = getenv("DBNAME");
    private $DBUSER = getenv("DBUSER");
    private $DBPASSWORD = getenv("DBPASSWORD");
    private $dbc;
    private function connect()
    {
        $this->dbc = new PDO("mysql:host=$this->DBHOST;dbname=$this->DBNAME", $this->DBUSER, $this->DBPASSWORD);
        if ($this->dbc->errorCode()) {
            die("Connection Error: " . $this->dbc->errorInfo());
        }
    }
    public function getConnection()
    {
        if ($this->dbc === null) {
            $this->connect();
        }
        return $this->dbc;
    }
}