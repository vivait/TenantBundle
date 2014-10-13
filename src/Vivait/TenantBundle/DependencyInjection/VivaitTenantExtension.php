<?php

namespace Vivait\TenantBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

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
	 * @param array $mergedConfig
	 * @param ContainerBuilder $container
	 */
	protected function loadInternal( array $mergedConfig, ContainerBuilder $container ) {
		// TODO: Implement loadInternal() method.
		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('services.yml');
	}
}
