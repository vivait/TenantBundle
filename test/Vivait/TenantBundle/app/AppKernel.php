<?php

namespace test\Vivait\TenantBundle\app;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Vivait\TenantBundle\Registry\TenantRegistry;

class AppKernel extends Kernel
{
    /**
     * @var TenantRegistry
     */
    private $tenantRegistry;

    const CONFIG_PATH = '/config/';

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
        $loader->load( __DIR__ . self::CONFIG_PATH . 'config_' . $this->getEnvironment() . '.yml' );
    }

    public function handle( Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true )
    {
        $this->tenantRegistry = \Vivait\TenantBundle\Bootstrap::createRegistry(
            new \Vivait\TenantBundle\Provider\ConfigProvider( __DIR__ . self::CONFIG_PATH ),
            new \Vivait\TenantBundle\Locator\HostnameLocator( $request )
        );
        $this->environment = 'tenant_' . $this->tenantRegistry->getCurrent()->getKey();

        return parent::handle( $request, $type, $catch );
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
}
