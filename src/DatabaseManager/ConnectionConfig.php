<?php

namespace LinkORB\Component\DatabaseManager;

class ConnectionConfig
{
    private $name;
    private $databasename;
    private $host;
    private $port = 3306;
    private $username;
    private $password;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setDatabaseName($databasename)
    {
        $this->databasename = $databasename;
    }

    public function getDatabaseName()
    {
        return $this->databasename;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getDsn()
    {
        return 'mysql:dbname=' . $this->databasename . ';host=' . $this->host . ';port=' . $this->port;
    }
}