<?php

namespace LinkORB\Component\DatabaseManager;

class DatabaseConfig
{
    private $name;
    private $connectionconfig = array();
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function addConnectionConfig(ConnectionConfig $connectionconfig)
    {
        $this->connectionconfig[$connectionconfig->getName()] = $connectionconfig;
    }

    public function getConnectionConfig($connectionkey)
    {
        return $this->connectionconfig[$connectionkey];
    }
    
    public function getConnectionConfigs()
    {
        return $this->connectionconfig;
    }
}
