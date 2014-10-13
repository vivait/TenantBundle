<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	/**
	 * @return array
	 */
	public function registerBundles()
	{
		return array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Vivait\TenantBundle\VivaitTenantBundle()
		);
	}

	/**
	 * @return null
	 */
	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
	}

	/**
	 * @return string
	 */
	public function getCacheDir()
	{
		return sys_get_temp_dir().'/VivaitTenantBundle/cache';
	}

	/**
	 * @return string
	 */
	public function getLogDir()
	{
		return sys_get_temp_dir().'/VivaitTenantBundle/logs';
	}
}