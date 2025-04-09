<?php

namespace MvpMarket\Database;

use PDO;

class Database
{
    private $DBHOST = "localhost:3306";
    private $DBNAME = "data";
    private $DBUSER = "root";
    private $DBPASSWORD = "";
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