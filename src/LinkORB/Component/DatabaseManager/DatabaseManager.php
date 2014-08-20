<?php

namespace LinkORB\Component\DatabaseManager;

class DatabaseManager
{
    public function getDatabaseConfigByDatabaseName($dbname)
    {
        $inidata = parse_ini_file('/share/config/database/' . $dbname . '.conf');
        
        $databaseconfig = new DatabaseConfig($dbname);
        
        $connectionconfig = new ConnectionConfig('default');

        $connectionconfig->setDatabaseName($inidata['name']);
        $connectionconfig->setHost($inidata['server']);
        $connectionconfig->setUsername($inidata['username']);
        $connectionconfig->setPassword($inidata['password']);

        $databaseconfig->addConnectionConfig($connectionconfig);
        return $databaseconfig;
    }
    
    public function getPdo($dbname, $connectionkey = 'default')
    {
        $databaseconfig = $this->getDatabaseConfigByDatabaseName($dbname);
        $connectionconfig = $databaseconfig->getConnectionConfig($connectionkey);
        $pdo = new \PDO(
            $connectionconfig->getDsn(),
            $connectionconfig->getUsername(),
            $connectionconfig->getPassword()
        );
        return $pdo;
    }
    
    public function getDbalConnection($dbname, $connectionkey = 'default')
    {
        $databaseconfig = $this->getDatabaseConfigByDatabaseName($dbname);
        $connectionconfig = $databaseconfig->getConnectionConfig($connectionkey);

        $dbalconfig = new \Doctrine\DBAL\Configuration();
        //..
        $connectionParams = array(
            'dbname' => $connectionconfig->getDatabaseName(),
            'user' => $connectionconfig->getusername(),
            'password' => $connectionconfig->getPassword(),
            'host' => $connectionconfig->getHost(),
            'driver' => 'pdo_mysql',
        );
        
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $dbalconfig);
        return $conn;
    }
}
