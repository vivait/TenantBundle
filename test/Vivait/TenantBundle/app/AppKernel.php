<?php

namespace test\Vivait\TenantBundle\app;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Vivait\TenantBundle\Kernel\TenantKernel;
use Vivait\TenantBundle\Locator\HostnameLocator;
use Vivait\TenantBundle\Provider\ConfigProvider;
use Vivait\TenantBundle\Provider\TenantProvider;

class AppKernel extends TenantKernel
{
    /**
     * @return \Symfony\Component\HttpKernel\Bundle\Bundle[]
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Vivait\TenantBundle\VivaitTenantBundle()
        );
    }

    /**
     * @return null
     */
    public function registerContainerConfiguration( LoaderInterface $loader )
    {
        $loader->load( __DIR__ . '/config/config_' . $this->getEnvironment() . '.yml' );
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/VivaitTenantBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir() . '/VivaitTenantBundle/logs';
    }

    /**
     * Provides an instance of a tenant provider
     *
     * @return TenantProvider
     */
    protected function getAllTenants()
    {
        $configProvider = new ConfigProvider( __DIR__ . '/config/' );
        return $configProvider->loadTenants();
    }

    /**
     * Provides the current tenant's key
     * @param Request $request
     * @return string The current tenant's key
     */
    protected function getCurrentTenantKey( Request $request )
    {
        return HostnameLocator::getTenantFromRequest( $request );
    }
}
