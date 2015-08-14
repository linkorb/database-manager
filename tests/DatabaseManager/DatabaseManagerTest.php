<?php

namespace LinkORB\Component\DatabaseManager\Tests;

use LinkORB\Component\DatabaseManager\DatabaseManager;

/**
 * Test class for DatabaseManager
 *
 * @author Igor Mukhin <igor.mukhin@gmail.com>
 */
class DatabaseManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValidUrl()
    {
        $dbmanager = new DatabaseManager();
        $this->assertFalse($dbmanager->isValidUrl('databasename'));
        $this->assertFalse($dbmanager->isValidUrl('invalid/databasename'));
        $this->assertTrue ($dbmanager->isValidUrl('mysql://host/databasename'));
    }

    public function testIsValidName()
    {
        $dbmanager = new DatabaseManager();
        $this->assertTrue ($dbmanager->isValidName('databasename'));
        $this->assertTrue ($dbmanager->isValidName('DATABASE_NAME'));
        $this->assertFalse($dbmanager->isValidName('invalid/databasename'));
        $this->assertFalse($dbmanager->isValidName('mysql://host/databasename'));
    }

    public function testGetDatabaseConfigByDatabaseName()
    {
        $path = realpath(__DIR__ . '/../fixtures');

        $dbmanager = new DatabaseManager($path);
        $databaseconfig = $dbmanager->getDatabaseConfigByDatabaseName('mydb');
        $connectionconfig = $databaseconfig->getConnectionConfig('default');

        $this->assertEquals('default', $connectionconfig->getName());
        $this->assertEquals('mydb', $connectionconfig->getDatabaseName());
        $this->assertEquals('localhost', $connectionconfig->getHost());
        $this->assertEquals('1234', $connectionconfig->getPort());
        $this->assertEquals('my_username', $connectionconfig->getUsername());
        $this->assertEquals('my_password', $connectionconfig->getPassword());
        $this->assertEquals('mysql:dbname=mydb;host=localhost;port=1234', $connectionconfig->getDsn());
    }

    public function testGetDatabaseConfigFromUrl()
    {
        $url = "mysql://my_username:my_password@localhost:1234/mydb";

        $dbmanager = new DatabaseManager();
        $databaseconfig = $dbmanager->getDatabaseConfigFromUrl($url);
        $connectionconfig = $databaseconfig->getConnectionConfig('default');

        $this->assertEquals('mydb', $connectionconfig->getDatabaseName());
        $this->assertEquals('localhost', $connectionconfig->getHost());
        $this->assertEquals('1234', $connectionconfig->getPort());
        $this->assertEquals('my_username', $connectionconfig->getUsername());
        $this->assertEquals('my_password', $connectionconfig->getPassword());
        $this->assertEquals('mysql:dbname=mydb;host=localhost;port=1234', $connectionconfig->getDsn());
    }

    public function testGetUrlByDatabaseName()
    {
        $expectedUrl = "mysql://my_username:my_password@localhost:1234/mydb";
        $path = realpath(__DIR__ . '/../fixtures');

        $dbmanager = new DatabaseManager($path);
        $this->assertEquals($dbmanager->getUrlByDatabaseName('mydb'), $dbmanager->getUrlByDatabaseName($expectedUrl));
    }

    public function testGetDatabaseConfig()
    {
        $expectedUrl = "mysql://my_username:my_password@localhost:1234/mydb";
        $path = realpath(__DIR__ . '/../fixtures');

        $dbmanager = new DatabaseManager($path);
        $this->assertInstanceOf('LinkORB\Component\DatabaseManager\DatabaseConfig', $dbmanager->getDatabaseConfig($expectedUrl));
    }

    /**
     * @expectedException LinkORB\Component\DatabaseManager\Exception\InvalidDatabaseException
     */
    public function testGetDatabaseConfigThrowsInvalidDatabaseException()
    {
        $dbmanager = new DatabaseManager();
        $dbmanager->getDatabaseConfig('bad/name');
    }

    /**
     * @expectedException LinkORB\Component\DatabaseManager\Exception\ConfigNotFoundException
     */
    public function testGetDatabaseConfigThrowsConfigNotFoundException()
    {
        $dbmanager = new DatabaseManager();
        $dbmanager->getDatabaseConfig('database_name_who_havent_config_file');
    }
}
