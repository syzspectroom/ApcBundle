<?php
/*
* This file is part of the Kelu\UserBundle
*
* Copyright (c) 2011 Luc Vandesype - http://www.neoplug.com
*/

namespace Kelu95\ApcBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
* ClearCommand.
*
* Lets you clear APC cache user or filehits  
*/
class ClearCommand extends BaseCommand
{
	/**
	* @see Command
	*/
    protected function configure()
    {
        $this
            ->setDefinition(array())
			->setDescription('Clear all APC cache user or opcode')
            ->addOption('opcode', null, InputOption::VALUE_NONE, 'Clear opcode cache')
            ->addOption('user', null, InputOption::VALUE_NONE, 'Clear user cache')
            ->setName('apc:clear');
    }

    /**
	* @see Command
	*/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		if(!$input->getOption('user') && !$input->getOption('opcode')){
			$output->writeln('Error : Cache type option must be --user or --opcode');
			return;
		}
		
		//Define cache type from passed option
		if($input->getOption('user')) $cache_type="user";
		else if($input->getOption('opcode')) $cache_type="opcode";
		
    	$cache = $this->container->get('apc_cache'); //get apc_cache service
		$cache->clearAll($cache_type); //clear
		
		//output + logger
		$output_str='APC clear all cache '.$cache_type.' from HOST : '.gethostname();
		
		$logger = $this->container->get('logger'); //get logger service
		$logger->info($output_str);
		
        $output->writeln($output_str);
    }
	
	
}

