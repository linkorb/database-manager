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
    private $dbmanager;
    private $expectedUrl = "mysql://my_username:my_password@localhost:1234/mydb";

    public function setUp()
    {
        $path = realpath(__DIR__ . '/../fixtures');
        $this->dbmanager = new DatabaseManager($path);
    }

    public function testIsValidUrl()
    {
        $this->assertFalse($this->dbmanager->isValidUrl('databasename'));
        $this->assertFalse($this->dbmanager->isValidUrl('invalid/databasename'));
        $this->assertTrue ($this->dbmanager->isValidUrl('mysql://host/databasename'));
    }

    public function testIsValidName()
    {
        $this->assertTrue ($this->dbmanager->isValidName('databasename'));
        $this->assertTrue ($this->dbmanager->isValidName('DATABASE_NAME'));
        $this->assertFalse($this->dbmanager->isValidName('invalid/databasename'));
        $this->assertFalse($this->dbmanager->isValidName('mysql://host/databasename'));
    }

    public function testGetDatabaseConfigByDatabaseName()
    {
        $databaseconfig = $this->dbmanager->getDatabaseConfigByDatabaseName('mydb');
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
        $databaseconfig = $this->dbmanager->getDatabaseConfigFromUrl($this->expectedUrl);
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
        $this->assertEquals($this->dbmanager->getUrlByDatabaseName('mydb'), $this->dbmanager->getUrlByDatabaseName($this->expectedUrl));
    }

    public function testGetDatabaseConfig()
    {
        $this->assertInstanceOf('LinkORB\Component\DatabaseManager\DatabaseConfig', $this->dbmanager->getDatabaseConfig($this->expectedUrl));
    }

    /**
     * @expectedException LinkORB\Component\DatabaseManager\Exception\InvalidDatabaseException
     */
    public function testGetDatabaseConfigThrowsInvalidDatabaseException()
    {
        $this->dbmanager->getDatabaseConfig('bad/name');
    }

    /**
     * @expectedException LinkORB\Component\DatabaseManager\Exception\ConfigNotFoundException
     */
    public function testGetDatabaseConfigThrowsConfigNotFoundException()
    {
        $this->dbmanager->getDatabaseConfig('database_name_who_havent_config_file');
    }
}
