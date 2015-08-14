<?php

namespace LinkORB\Component\DatabaseManager;

use LinkORB\Component\DatabaseManager\Exception\InvalidDatabaseException;
use LinkORB\Component\DatabaseManager\Exception\ConfigNotFoundException;

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
     * @return DatabaseConfig
     */
    public function getDatabaseConfigByDatabaseName($dbname)
    {
        $filename = sprintf(
            '%s/%s.conf',
            rtrim($this->getConfigPath(), '/'),
            $dbname
        );

        if (!file_exists($filename)) {
            throw new ConfigNotFoundException($dbname);
        }

        $inidata = parse_ini_file($filename);
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
     * @param  string $url
     * @return DatabaseConfig
     */
    public function getDatabaseConfigFromUrl($url)
    {
        $url = parse_url($url);
        $dbname = trim($url['path'], '/');

        $databaseconfig = new DatabaseConfig($dbname);
        $connectionconfig = new ConnectionConfig('default');

        $connectionconfig->setDatabaseName($dbname);
        $connectionconfig->setHost($url['host']);
        if (isset($url['port'])) {
            $connectionconfig->setPort($url['port']);
        }
        $connectionconfig->setUsername($url['user']);
        $connectionconfig->setPassword($url['pass']);

        $databaseconfig->addConnectionConfig($connectionconfig);
        return $databaseconfig;
    }

    /**
     * @param  string $dbname URL or
     * @return DatabaseConfig
     */
    public function getDatabaseConfig($dbname)
    {
        switch (true)
        {
            case $this->isValidUrl($dbname):
                return $this->getDatabaseConfigFromUrl($dbname);
                break;

            case $this->isValidName($dbname):
                return $this->getDatabaseConfigByDatabaseName($dbname);
                break;

            default:
                throw new InvalidDatabaseException($dbname);
        }
    }

    /**
     * @param  string $dbname
     * @param  string $connectionkey
     * @return PDO
     */
    public function getPdo($dbname, $connectionkey = 'default')
    {
        $databaseconfig = $this->getDatabaseConfig($dbname);
        $connectionconfig = $databaseconfig->getConnectionConfig($connectionkey);
        $pdo = new PDO(
            $connectionconfig->getDsn(),
            $connectionconfig->getUsername(),
            $connectionconfig->getPassword()
        );
        return $pdo;
    }

    /**
     * @param  string $dbname
     * @return string
     */
    public function getUrlByDatabaseName($dbname, $connectionkey = 'default')
    {
        if ($this->isValidUrl($dbname)) {
            return $dbname;
        }

        $databaseconfig = $this->getDatabaseConfigByDatabaseName($dbname);
        $connectionconfig = $databaseconfig->getConnectionConfig($connectionkey);

        return sprintf(
            "%s://%s:%s@%s:%s/%s",
            $connectionconfig->getDriver(),
            $connectionconfig->getUsername(),
            $connectionconfig->getPassword(),
            $connectionconfig->getHost(),
            $connectionconfig->getPort(),
            $connectionconfig->getDatabaseName()
        );
    }

    /**
     * @param  string  $url
     * @return boolean
     */
    public function isValidUrl($url)
    {
        return false !== filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * @param  string  $name
     * @return boolean
     */
    public function isValidName($name)
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $name) != false;
    }
}
