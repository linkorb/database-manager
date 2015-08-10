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
}
