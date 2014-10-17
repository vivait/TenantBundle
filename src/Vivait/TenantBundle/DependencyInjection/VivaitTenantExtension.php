<?php

namespace Vivait\TenantBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Vivait\TenantBundle\Registry\TenantRegistry;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class VivaitTenantExtension extends ConfigurableExtension
{


    /**
     * Configures the passed container according to the merged configuration.
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('vivait.tenant.name', $config['tenant']['name']);
        $container->setParameter('vivait.tenant.attributes', $config['tenant']['attributes']);

    }
}
