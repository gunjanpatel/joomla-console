<?php
/**
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		Mozilla Public License, version 2.0
 * @link		http://github.com/joomlatools/joomla-console for the canonical source repository
 */

namespace Joomlatools\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

abstract class DeployAbstract extends Command
{
    protected $site;

    protected $user;

    protected $repository;

    protected $deploy_to;

    protected $backup;

    protected $branch;

    protected $remote_cache;

    protected $key_path;

    protected $app;

    protected $database;

    protected $configuration;

    protected function configure()
    {
        $this->addArgument(
            'site',
            InputArgument::REQUIRED,
            'Alphanumeric site name. Also used in the site URL with .dev domain'
        )->addOption(
            'www',
            null,
            InputOption::VALUE_REQUIRED,
            "Web server root",
            '/var/www'
        )
        ->addOption(
            'environment',
            null,
            InputOption::VALUE_REQUIRED,
            "Which deploy environment would you like to use",
            'development'
        )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->site         = $input->getArgument('site');
        $this->www          = $input->getOption('www');
        $this->environment  = $input->getOption('environment');

        $this->target_db  = 'sites_'.$this->site;
        $this->target_dir = $this->www.'/'.$this->site;

        if(file_exists($this->target_dir . '/deploy/' . $this->environment . '.yml'))
        {
            $yaml = new Parser;
            $this->configuration = $yaml->parse(file_get_contents($this->target_dir . '/deploy/' . $this->environment . '.yml'));
        }
    }
}