<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 30/10/2020
 * Time: 09:22
 */

namespace FormLibrary\Src;

use mysqli;

class Database
{
    private $host;
    
    private $user;
    
    private $pass;
    
    private $port;
    
    private $db;

    /**
     * Database constructor - set up connection
     */
    public function __construct()
    {
        $this->setConnectionDetails();
        return $this->connect();
    }

    /**
     * setConnectionDetails - get and assign connection properties from config
     */
    private function setConnectionDetails()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USERNAME');
        $this->pass = getenv('DB_PASSWORD');
        $this->port = getenv('DB_POST');
        $this->db = getenv('DB_NAME');
    }

    /**
     * connect - establishes and returns database connection
     * @return mysqli
     */
    private function connect()
    {
        $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);
        if ($mysqli->connect_errno) {
            echo "Unable to establish database connection.";
            die();
        }
        return $mysqli;
    }
}