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

abstract class SiteAbstract extends Command
{
    protected $site;
    protected $www;

    protected $target_dir;
    protected $target_db;
    protected $target_db_prefix = 'sites_';

    protected $mysql;

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
            '/var/www/html'
        )
        ->addOption(
            'mysql',
            null,
            InputOption::VALUE_REQUIRED,
            "MySQL credentials in the form of user:password",
            'root:gunjan'
        )
        ->addOption(
            'mysql_db_prefix',
            null,
            InputOption::VALUE_OPTIONAL,
            "MySQL database prefix (default: sites_)",
            'sites_'
        )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->site       = $input->getArgument('site');
        $this->www        = $input->getOption('www');
        $this->target_db_prefix = $input->getOption('mysql_db_prefix');

        $this->target_db  = $this->target_db_prefix.$this->site;
        $this->target_dir = $this->www.'/'.$this->site;

        $credentials = explode(':', $input->getOption('mysql'), 2);
        $this->mysql = (object) array('user' => $credentials[0], 'password' => $credentials[1]);
    }
}
