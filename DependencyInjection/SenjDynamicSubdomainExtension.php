<?php

namespace Senj\DynamicSubdomainBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SenjDynamicSubdomainExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('senj_dynamic_subdomain.base_host', $config['base_host']);
        $container->setParameter('senj_dynamic_subdomain.parameter_name', $config['parameter_name']);
        $container->setParameter('senj_dynamic_subdomain.entity', $config['entity']);
        $container->setParameter('senj_dynamic_subdomain.method', $config['method']);
        $container->setParameter('senj_dynamic_subdomain.property', $config['property']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
