<?php


namespace LinkORB\Component\DatabaseManager\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

use LinkORB\Component\DatabaseManager\DatabaseManager;

/**
 * ConnectionConfigCommand retrieves configuration info
 *
 */
class DatabaseLoadFixtureCommand extends Command
{
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('database:runfixture')
            ->setDefinition(array(
                new InputArgument('dbname', InputArgument::REQUIRED, 'Database name'),
                new InputArgument('filename', InputArgument::REQUIRED, 'Fixture (yml) filename')
            ))
            ->setDescription('Load fixture into database')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbname = $input->getArgument('dbname');
        $filename = $input->getArgument('filename');
        
        $manager = new DatabaseManager();

        $pdo = $manager->getPdo($dbname, 'default');
        
        $dataset = new \PHPUnit_Extensions_Database_DataSet_YamlDataSet($filename);

        $connection = new \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection($pdo, $dbname);
        $databasetester = new \PHPUnit_Extensions_Database_DefaultTester($connection);
        $setupoperation = \PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT();
        $databasetester->setSetUpOperation($setupoperation);
        $databasetester->setDataSet($dataset);
        $databasetester->onSetUp();

        
        
        
        
        
    }
}
