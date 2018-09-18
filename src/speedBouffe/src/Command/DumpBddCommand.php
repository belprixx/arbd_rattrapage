<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DumpBddCommand extends Command
{
    protected static $defaultName = 'dump-bdd';


    private $database;
    private $username;
    private $password;
    private $port;

    private $parameterBag;

    /** filesystem utility */
    private $fs;

    public function __construct(ParameterBagInterface $parameterBag, $name = null)
    {
        $this->parameterBag = $parameterBag;
        parent::__construct($name);
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->database= $this->parameterBag->get('database_host');
        $this->username= $this->parameterBag->get('database_user');
        $this->password= $this->parameterBag->get('database_password');
        $this->port= $this->parameterBag->get('database_port');
        $this->fs = new Filesystem();
    }

    protected function configure()
    {
        $this
            ->setDescription('Dump database')
            ->addArgument('file', InputArgument::REQUIRED, 'Absolute path for the file you need to dump database to.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->database = $this->getContainer()->getParameter('database_name') ;
        $this->username = $this->getContainer()->getParameter('database_user') ;
        $this->password = $this->getContainer()->getParameter('database_password') ;
        $this->path = $input->getArgument('file') ;
        $this->fs = new Filesystem() ;
        $this->output->writeln(sprintf('<comment>Dumping <fg=green>%s</fg=green> to <fg=green>%s</fg=green> </comment>', $this->database, $this->path ));
        $this->createDirectoryIfRequired();
        $this->dumpDatabase();
        $output->writeln('<comment>All done.</comment>');
    }

    private function createDirectoryIfRequired() {
        if (! $this->fs->exists($this->path)){
            $this->fs->mkdir(dirname($this->path));
        }
    }

    private function dumpDatabase()
    {
        $cmd = sprintf('mysqldump -B %s -u %s --password=%s' // > %s'
            , $this->database
            , $this->username
            , $this->password
        );

        $result = $this->runCommand($cmd);

        if($result['exit_status'] > 0) {
            throw new \Exception('Could not dump database: ' . var_export($result['output'], true));
        }

        $this->fs->dumpFile($this->path, $result);
    }
}
