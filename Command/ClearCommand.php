<?php
/*
* This file is part of the Kelu\UserBundle
*
* Copyright (c) 2011 Luc Vandesype - http://www.neoplug.com
*/

namespace Allblacks\ApcBundle\Command;

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
            ->setName('apc:clear')
            ->setDescription('Clear all APC cache user or filehits')
            ->setDefinition(array(
                new InputArgument('type', InputArgument::REQUIRED, 'The cache type (user or file)')
           ))
            ->setHelp(<<<EOT
The <info>apc:clear</info> command that clear all APC cache :
<info>php app/console apc:clear type</info>
The type argument is required
EOT
            );
    }

    /**
	* @see Command
	*/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$logger = $this->container->get('logger'); //get logger service
		
		$cache_type=''; //empty for filehits
		if($input->getArgument('type')=='user') $cache_type='user';

    	$cache = $this->container->get('apc_cache'); //get apc_cache service
		$cache->clearAll($cache_type);
		
		$output_str='APC clear all cache '.$input->getArgument('type').' from HOST : '.gethostname();
		
		$logger->info($output_str);
        $output->writeln($output_str);
    }
	
	/**
	* @see Command
	*/
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    	$logger = $this->container->get('logger');

    	if (!$input->getArgument('type')) {
            $type = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please specify the cache to clear [user or file]:',
                function($type)
                {
                    if (empty($type) || ($type!='user' && $type!='file')) {
                        throw new \Exception('Cache type must be user our file');
                    }

                    return $type;
                }
            );
            $input->setArgument('type', $type);
        }
    }

}

