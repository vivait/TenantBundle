<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * @return array<Symfony\Component\HttpKernel\Bundle\Bundle\Bundle>
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
        try {
            $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
        }
        catch (InvalidArgumentException $e) {
            $loader->load(__DIR__.'/config/config_tenant_default.yml');
        }
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
