<?php


namespace LinkORB\Component\DatabaseManager\Command;

use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

use LinkORB\Component\DatabaseManager\DatabaseManager;
use RuntimeException;

/**
 * ConnectionConfigCommand retrieves configuration info
 *
 */
class DatabaseLoadSchemaCommand extends Command
{
    private $command;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('database:loadschema')
            ->setDefinition(array(
                new InputArgument('dbname', InputArgument::REQUIRED, 'Database name'),
                new InputArgument('filename', InputArgument::REQUIRED, 'Schema (xml) filename')
            ))
            ->addOption(
                'apply',
                null,
                InputOption::VALUE_NONE,
                'Apply required schema changes'
            )
            ->setDescription('Load schema into database')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbname = $input->getArgument('dbname');
        $filename = $input->getArgument('filename');
        $apply = $input->getOption('apply');
        
        $manager = new DatabaseManager();

        $conn = $manager->getDbalConnection($dbname, 'default');
        
        
        $toSchema = new \Doctrine\DBAL\Schema\Schema();
        
        $xml = simplexml_load_file($filename);
        
        foreach ($xml->table as $tableNode) {
            //echo ;
            //echo $child->getName() . ": " . $child . "<br>";
            $table = $toSchema->createTable((string)$tableNode['name']);

            $table->addColumn('id', 'integer', array("unsigned" => true, 'autoincrement' => true));
            $table->setPrimaryKey(array("id"));

            foreach ($tableNode->column as $columnNode) {
                $columntype = trim($columnNode['type'], ' )');
                $part = explode('(', $columntype);
                $options = array();
                switch($part[0]) {
                    case "int":
                        $type = 'int';
                        break;
                    case "text":
                        $type = 'text';
                        break;
                    case "varchar":
                        $type = 'string';
                        $options['length'] = $part[1];
                        break;
                    default:
                        throw new RuntimeException("Unsupported type: " . $columntype);
                }
                $table->addColumn((string)$columnNode['name'], $type, $options);
            }
            
        }
        //exit('ok doei');
        
        /*
        $t->addColumn("id", "integer", array("unsigned" => true));
        $t->addColumn("apikey", "string", array("length" => 128));
        $t->addColumn("apisecret", "string", array("length" => 128));
        $t->addColumn("roles", "string", array("length" => 128));
        $t->setPrimaryKey(array("apikey"));
        $t->addUniqueIndex(array("apikey"));
        //$schema->createSequence("my_table_seq");
        */

        $platform = $conn->getDatabasePlatform();

        //$queries = $toSchema->toSql($platform); // get queries to create this schema from zero.
        //print_r($queries);

        $sm = $conn->getSchemaManager();
        $fromSchema = $sm->createSchema();
        
        $comparator = new \Doctrine\DBAL\Schema\Comparator();
        $schemaDiff = $comparator->compare($fromSchema, $toSchema);

        //$queries = $schemaDiff->toSql($myPlatform); // queries to get from one to another schema.
        $queries = $schemaDiff->toSaveSql($platform);

        //$queries = $fromSchema->getMigrateToSql($toSchema, $platform);

        //print_r($sql);
        if (count($queries)>0) {
            if (!$apply) {
                echo "CHANGES: The following SQL statements need to be executed to synchronise the schema (use --apply)\n";
                foreach ($queries as $query) {
                    echo "SQL: " . $query . "\n";
                    //$stmt = $conn->query($query);
                }
            } else {
                foreach ($queries as $query) {
                    echo "RUNNING: " . $query . "\n";
                    $stmt = $conn->query($query);
                }
            }

        } else {
            echo "No schema changes required\n";
        }
        
        
        
    }
}
