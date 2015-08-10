<?php

namespace LinkORB\Component\DatabaseManager;

use PDO;

class DatabaseManager
{
    protected $configPath = '/share/config/database/';

    /**
     * @param string $configPath Path to configuration files
     */
    public function __construct($configPath = null)
    {
        if (!is_null($configPath)) {
            $this->configPath = $configPath;
        }
    }

    /**
     * Return path where configs stored at.
     *
     * @return string
     */
    public function getConfigPath()
    {
        return $this->configPath;
    }

    /**
     * @param  string $dbname
     * @return ConnectionConfig
     */
    public function getDatabaseConfigByDatabaseName($dbname)
    {
        $inidata = parse_ini_file(sprintf(
            '%s/%s.conf',
            rtrim($this->getConfigPath(), '/'),
            $dbname
        ));

        $databaseconfig = new DatabaseConfig($dbname);

        $connectionconfig = new ConnectionConfig('default');

        $connectionconfig->setDatabaseName($inidata['name']);
        $connectionconfig->setHost($inidata['server']);
        if (isset($inidata['port'])) {
            $connectionconfig->setPort($inidata['port']);
        }
        $connectionconfig->setUsername($inidata['username']);
        $connectionconfig->setPassword($inidata['password']);

        $databaseconfig->addConnectionConfig($connectionconfig);
        return $databaseconfig;
    }

    /**
     * @param  string $dbname
     * @param  string $connectionkey
     * @return PDO
     */
    public function getPdo($dbname, $connectionkey = 'default')
    {
        $databaseconfig = $this->getDatabaseConfigByDatabaseName($dbname);
        $connectionconfig = $databaseconfig->getConnectionConfig($connectionkey);
        $pdo = new PDO(
            $connectionconfig->getDsn(),
            $connectionconfig->getUsername(),
            $connectionconfig->getPassword()
        );
        return $pdo;
    }
}
