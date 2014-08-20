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
class ConnectionConfigCommand extends Command
{
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('connection:config')
            ->setDefinition(array(
                new InputArgument('dbname', InputArgument::REQUIRED, 'Database name')
            ))
            ->setDescription('Display datatbase configuration info')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbname = $input->getArgument('dbname');
        
        $manager = new DatabaseManager();
        $databaseconfig = $manager->getDatabaseConfigByDatabaseName($dbname);
        
        echo "NAME: [" . $databaseconfig->getName() . "]\n";
        
        foreach ($databaseconfig->getConnectionConfigs() as $connectionconfig) {
            echo "    Connection [" . $connectionconfig->getName() . "]\n";
            echo "        dsn:  [" . $connectionconfig->getDsn() . "]\n";
            echo "        username:  [" . $connectionconfig->getUsername() . "]\n";
            echo "        password:  [" . $connectionconfig->getPassword() . "]\n";
            echo "        host:  [" . $connectionconfig->getHost() . "]\n";
            echo "        port:  [" . $connectionconfig->getPort() . "]\n";
            echo "\n";
        }
        
        $pdo = $manager->getPdo($dbname, 'default');
        
        
        
    }
}
