<?php

namespace Kelu95\ApcBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApcExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition('apc_cache'))
        {
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('apc_cache_services.xml');
            
        }
    }

    public function getAlias()
    {
        return 'apc';
    }
}