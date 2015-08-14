<?php

namespace LinkORB\Component\DatabaseManager;

class ConnectionConfig
{
    private $name;
    private $driver = 'mysql';
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

    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function setDatabaseName($databasename)
    {
        $this->databasename = $databasename;

        return $this;
    }

    public function getDatabaseName()
    {
        return $this->databasename;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDsn()
    {
        return sprintf(
            "%s:dbname=%s;host=%s;port=%s",
            $this->driver,
            $this->databasename,
            $this->host,
            $this->port
        );
    }
}
